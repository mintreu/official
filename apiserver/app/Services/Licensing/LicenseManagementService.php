<?php

declare(strict_types=1);

namespace App\Services\Licensing;

use App\Enums\LicenseType;
use App\Models\Api\ApiSpace;
use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\Plan;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LicenseManagementService
{
    /**
     * Create/update a subscription license for a user + API product.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function upsertSubscriptionLicense(
        User $user,
        Product $product,
        Plan $plan,
        array $attributes = []
    ): License {
        $license = License::query()->firstOrNew([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'type' => LicenseType::ApiSubscription,
        ]);

        $license->fill(array_merge([
            'plan_id' => $plan->id,
            'type' => LicenseType::ApiSubscription,
            'is_active' => true,
            'usage_count' => 0,
            'max_usage' => null,
        ], $attributes));

        $this->ensureIdentity($license);
        $license->save();

        return $license->fresh();
    }

    /**
     * Create/update a non-subscription (commercial/free) license for a user + product.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function upsertProductLicense(
        User $user,
        Product $product,
        LicenseType $type,
        array $attributes = []
    ): License {
        $license = License::query()->firstOrNew([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'type' => $type,
        ]);

        $license->fill(array_merge([
            'type' => $type,
            'is_active' => true,
        ], $attributes));

        $this->ensureIdentity($license);
        $license->save();

        return $license->fresh();
    }

    public function resolveOwnedLicense(int $userId, string $licenseUuid): License
    {
        return License::query()
            ->with(['product', 'product.engagement', 'product.activeSources', 'plan', 'apiKey.plan'])
            ->where('user_id', $userId)
            ->where('uuid', $licenseUuid)
            ->firstOrFail();
    }

    /**
     * @return array{valid: bool, status: string, reason: string|null, days_until_expiration: int|null, remaining_usages: int|null}
     */
    public function validateLicense(License $license): array
    {
        $status = $this->determineStatus($license);

        return [
            'valid' => $status === 'active',
            'status' => $status,
            'reason' => match ($status) {
                'disabled' => 'License disabled.',
                'expired' => 'License expired.',
                'usage_limit' => 'Usage limit reached.',
                default => null,
            },
            'days_until_expiration' => $license->getDaysUntilExpiration(),
            'remaining_usages' => $license->getRemainingUsages(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function buildUserInsights(User $user): array
    {
        $licenses = $user->licenses()
            ->with(['product', 'plan', 'apiKey.plan'])
            ->orderByDesc('created_at')
            ->get();

        $active = $licenses->filter(fn (License $license): bool => $this->determineStatus($license) === 'active');
        $activeApi = $active->filter(fn (License $license): bool => in_array($license->product?->type?->value, ['api_service', 'api_referral'], true));
        $expiringSoon = $active->filter(fn (License $license): bool => $license->expires_at && $license->expires_at->lte(now()->addDays(7)));
        $spaces = ApiSpace::query()->where('user_id', $user->id)->get();

        $siteBilling = $activeApi->map(function (License $license) use ($spaces): array {
            $apiKey = $license->apiKey;
            $product = $license->product;
            $plan = $license->plan;
            $productMeta = (array) ($product?->meta ?? []);
            $planLimits = (array) ($plan?->limits ?? []);
            $includedSites = (int) ($planLimits['sites'] ?? $productMeta['included_sites'] ?? 1);
            $siteAddonMonthly = (float) ($productMeta['site_addon_monthly_price'] ?? 0);
            $usedSites = $apiKey ? (int) $spaces->where('api_key_id', $apiKey->id)->count() : 0;
            $extraSites = max(0, $usedSites - $includedSites);

            return [
                'license_uuid' => $license->uuid,
                'product_slug' => $product?->slug,
                'plan' => $plan?->slug,
                'included_sites' => $includedSites,
                'used_sites' => $usedSites,
                'extra_sites' => $extraSites,
                'site_addon_monthly_price' => $siteAddonMonthly,
                'estimated_extra_monthly_cost' => round($extraSites * $siteAddonMonthly, 2),
            ];
        })->values();

        return [
            'totals' => [
                'licenses' => $licenses->count(),
                'active_licenses' => $active->count(),
                'active_api_subscriptions' => $activeApi->count(),
                'active_api_projects' => $activeApi->groupBy('product_id')->count(),
                'expiring_soon' => $expiringSoon->count(),
                'spaces' => $spaces->count(),
            ],
            'billing_model' => [
                'recommended' => 'overall_plus_site_addon',
                'why' => 'One product subscription reduces friction; site addon keeps pricing fair as customer scales.',
            ],
            'site_billing' => $siteBilling,
            'project_insights' => $activeApi
                ->groupBy('product_id')
                ->map(function (Collection $projectLicenses, int|string $productId) use ($spaces): array {
                    /** @var License $primary */
                    $primary = $projectLicenses->first();
                    $product = $primary->product;
                    $projectSpaces = $spaces->where('product_id', (int) $productId);

                    return [
                        'product_id' => $product?->id,
                        'product_slug' => $product?->slug,
                        'product_title' => $product?->title,
                        'active_licenses' => $projectLicenses->count(),
                        'spaces' => $projectSpaces->count(),
                        'requests_this_month' => (int) $projectSpaces->sum('requests_this_month'),
                        'requests_today' => (int) $projectSpaces->sum('requests_today'),
                        'latest_activity_at' => optional(
                            $projectSpaces->pluck('last_request_at')
                                ->filter()
                                ->sortDesc()
                                ->first()
                        )?->toIsoString(),
                    ];
                })
                ->values(),
            'upcoming_renewals' => $expiringSoon->map(fn (License $license): array => [
                'license_uuid' => $license->uuid,
                'product_slug' => $license->product?->slug,
                'plan' => $license->plan?->slug,
                'expires_at' => $license->expires_at?->toIsoString(),
                'days_left' => $license->expires_at ? now()->diffInDays($license->expires_at, false) : null,
            ])->values(),
        ];
    }

    private function ensureIdentity(License $license): void
    {
        if (empty($license->uuid)) {
            $license->uuid = (string) Str::uuid();
        }

        if (empty($license->license_key)) {
            $license->license_key = License::generateKey();
        }
    }

    private function determineStatus(License $license): string
    {
        if (! $license->is_active) {
            return 'disabled';
        }

        if ($license->isExpired()) {
            return 'expired';
        }

        if ($license->isUsageLimitReached()) {
            return 'usage_limit';
        }

        return 'active';
    }
}
