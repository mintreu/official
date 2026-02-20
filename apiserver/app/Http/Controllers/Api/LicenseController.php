<?php

namespace App\Http\Controllers\Api;

use App\Enums\LicenseType;
use App\Http\Controllers\Controller;
use App\Models\Api\ApiKey;
use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\Plan;
use App\Models\Products\ProductSource;
use App\Services\Saas\ChildVendorProvisioningService;
use App\Services\Licensing\LicenseManagementService;
use App\Services\Saas\SubscriptionBillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class LicenseController extends Controller
{
    private const DEFAULT_RENEW_DAYS = 30;

    public function subscribe(
        Request $request,
        ChildVendorProvisioningService $provisioningService,
        SubscriptionBillingService $subscriptionBillingService,
        LicenseManagementService $licenseManagementService
    ): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'product_slug' => ['required', 'string', Rule::exists('products', 'slug')],
            'plan_id' => ['nullable', 'integer', Rule::exists('plans', 'id')],
            'site_slug' => ['nullable', 'string', 'max:120'],
            'site_name' => ['nullable', 'string', 'max:160'],
            'site_domain' => ['nullable', 'string', 'max:255'],
            'vendor_name' => ['nullable', 'string', 'max:160'],
        ]);

        $product = Product::query()
            ->where('slug', $validated['product_slug'])
            ->whereIn('type', ['api_service', 'api_referral'])
            ->first();

        if (! $product) {
            return response()->json(['message' => 'Only API products can be subscribed here.'], 422);
        }

        $plan = Plan::query()
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->when(
                ! empty($validated['plan_id']),
                fn ($query) => $query->where('id', (int) $validated['plan_id'])
            )
            ->orderBy('sort_order')
            ->first();

        if (! $plan) {
            return response()->json(['message' => 'Selected plan is invalid for this API product.'], 422);
        }

        $currentLicense = License::query()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('type', LicenseType::ApiSubscription)
            ->latest('id')
            ->first();

        $license = $licenseManagementService->upsertSubscriptionLicense(
            user: $user,
            product: $product,
            plan: $plan,
            attributes: [
                'usage_count' => 0,
                'max_usage' => null,
                'expires_at' => $currentLicense?->expires_at && $currentLicense->expires_at->isFuture()
                    ? $currentLicense->expires_at
                    : now()->addMonth(),
                'is_active' => true,
            ]
        );
        $isNewLicense = ! $currentLicense;

        $plainKey = null;
        $existingKey = $license->apiKey;

        if (! $existingKey) {
            $keyData = ApiKey::generateKey('sk_live');
            $plainKey = $keyData['key'];

            ApiKey::create([
                'product_id' => $product->id,
                'license_id' => $license->id,
                'plan_id' => $plan->id,
                'user_id' => $user->id,
                'key_hash' => $keyData['hash'],
                'key_prefix' => $keyData['prefix'],
                'name' => "{$product->title} - {$plan->name}",
                'environment' => 'prod',
                'allowed_domains' => [],
                'ip_whitelist' => [],
                'is_active' => true,
                'expires_at' => $license->expires_at,
            ]);
        } else {
            $existingKey->update([
                'plan_id' => $plan->id,
                'is_active' => true,
                'expires_at' => $license->expires_at,
            ]);
        }

        $license->load(['product', 'product.engagement', 'product.activeSources', 'plan', 'apiKey.plan']);
        $billing = $subscriptionBillingService->chargeSubscription($user, $license, $plan, $product);

        $provisioning = $provisioningService->provisionFromSubscription(
            $license,
            $product,
            $user,
            $plan,
            $license->apiKey,
            [
                'site_slug' => $validated['site_slug'] ?? null,
                'site_name' => $validated['site_name'] ?? null,
                'site_domain' => $validated['site_domain'] ?? null,
                'vendor_name' => $validated['vendor_name'] ?? null,
            ]
        );

        return response()->json([
            'message' => $isNewLicense ? 'Subscription created successfully.' : 'Subscription updated successfully.',
            'data' => $this->mapLicense($license),
            'upgrade_options' => $this->mapUpgradePlans($license),
            'matched_frontends' => $this->mapMatchedFrontends($license->product),
            'plain_api_key' => $plainKey,
            'billing' => $billing,
            'provisioning' => $provisioning,
        ], $isNewLicense ? 201 : 200);
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $perPage = max(1, min(24, (int) $request->integer('per_page', 6)));
        $type = (string) $request->query('type', 'all');

        $licensesQuery = $user->licenses()
            ->with([
                'product',
                'product.engagement',
                'product.activeSources',
                'plan',
                'apiKey.plan',
            ])
            ->orderByDesc('created_at');

        if ($type === 'download') {
            $licensesQuery->whereHas('product', fn ($query) => $query->where('type', 'downloadable'));
        } elseif ($type === 'api') {
            $licensesQuery->whereHas('product', fn ($query) => $query->whereIn('type', ['api_service', 'api_referral']));
        }

        /** @var LengthAwarePaginator $licenses */
        $licenses = $licensesQuery->paginate($perPage)->withQueryString();
        $payload = collect($licenses->items())->map(fn (License $license) => $this->mapLicense($license));

        return response()->json([
            'data' => $payload,
            'meta' => [
                'current_page' => $licenses->currentPage(),
                'from' => $licenses->firstItem(),
                'last_page' => $licenses->lastPage(),
                'per_page' => $licenses->perPage(),
                'to' => $licenses->lastItem(),
                'total' => $licenses->total(),
            ],
            'filters' => [
                'type' => in_array($type, ['all', 'download', 'api'], true) ? $type : 'all',
            ],
            'feature_flags' => $this->featureFlags(),
        ]);
    }

    public function show(
        Request $request,
        string $licenseUuid,
        LicenseManagementService $licenseManagementService
    ): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $record = $licenseManagementService->resolveOwnedLicense($user->id, $licenseUuid);

        return response()->json([
            'data' => $this->mapLicense($record),
            'upgrade_options' => $this->mapUpgradePlans($record),
            'matched_frontends' => $this->mapMatchedFrontends($record->product),
        ]);
    }

    public function updateApiKey(
        Request $request,
        string $licenseUuid,
        LicenseManagementService $licenseManagementService
    ): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $record = $licenseManagementService->resolveOwnedLicense($user->id, $licenseUuid);

        /** @var ApiKey|null $apiKey */
        $apiKey = $record->apiKey;
        if (! $apiKey) {
            return response()->json(['message' => 'API key is not available for this license.'], 422);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'nullable', 'string', 'max:120'],
            'environment' => ['sometimes', Rule::in(['prod', 'dev'])],
            'allowed_domains' => ['sometimes', 'array'],
            'allowed_domains.*' => ['string', 'max:255'],
            'ip_whitelist' => ['sometimes', 'array'],
            'ip_whitelist.*' => ['ip'],
        ]);

        $apiKey->fill($validated);
        $apiKey->save();

        return response()->json([
            'message' => 'API credentials updated successfully.',
            'data' => $this->mapLicense($record->fresh(['product', 'product.engagement', 'product.activeSources', 'plan', 'apiKey.plan'])),
        ]);
    }

    public function regenerateApiKey(
        Request $request,
        string $licenseUuid,
        LicenseManagementService $licenseManagementService
    ): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $record = $licenseManagementService->resolveOwnedLicense($user->id, $licenseUuid);

        /** @var ApiKey|null $apiKey */
        $apiKey = $record->apiKey;
        if (! $apiKey) {
            return response()->json(['message' => 'API key is not available for this license.'], 422);
        }

        $keyData = ApiKey::generateKey('sk_live');
        $apiKey->update([
            'key_hash' => $keyData['hash'],
            'key_prefix' => $keyData['prefix'],
            'is_active' => true,
            'expires_at' => $record->expires_at,
        ]);

        return response()->json([
            'message' => 'API key regenerated successfully.',
            'data' => $this->mapLicense($record->fresh(['product', 'product.engagement', 'product.activeSources', 'plan', 'apiKey.plan'])),
            'plain_api_key' => $keyData['key'],
        ]);
    }

    public function upgrade(
        Request $request,
        string $licenseUuid,
        LicenseManagementService $licenseManagementService
    ): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $record = $licenseManagementService->resolveOwnedLicense($user->id, $licenseUuid);

        $validated = $request->validate([
            'plan_id' => ['required', 'integer', Rule::exists('plans', 'id')],
        ]);

        $plan = Plan::query()
            ->where('id', (int) $validated['plan_id'])
            ->where('product_id', $record->product_id)
            ->where('is_active', true)
            ->first();

        if (! $plan) {
            return response()->json(['message' => 'Selected plan is invalid for this product.'], 422);
        }

        $record->plan_id = $plan->id;
        $record->expires_at = now()->addMonth();
        $record->save();

        if ($record->apiKey) {
            $record->apiKey->update(['plan_id' => $plan->id]);
        }

        return response()->json([
            'message' => 'License upgraded successfully.',
            'data' => $this->mapLicense($record->fresh(['product', 'product.engagement', 'product.activeSources', 'plan', 'apiKey.plan'])),
            'upgrade_options' => $this->mapUpgradePlans($record),
            'matched_frontends' => $this->mapMatchedFrontends($record->product),
        ]);
    }

    public function renew(
        Request $request,
        string $licenseUuid,
        LicenseManagementService $licenseManagementService
    ): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $record = $licenseManagementService->resolveOwnedLicense($user->id, $licenseUuid);

        $validated = $request->validate([
            'days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        $days = (int) ($validated['days'] ?? self::DEFAULT_RENEW_DAYS);
        $baseDate = $record->expires_at && $record->expires_at->isFuture()
            ? $record->expires_at->copy()
            : now();

        $record->update([
            'is_active' => true,
            'expires_at' => $baseDate->addDays($days),
        ]);

        if ($record->apiKey) {
            $record->apiKey->update([
                'is_active' => true,
                'expires_at' => $record->expires_at,
            ]);
        }

        return response()->json([
            'message' => 'Subscription renewed successfully.',
            'data' => $this->mapLicense($record->fresh(['product', 'product.engagement', 'product.activeSources', 'plan', 'apiKey.plan'])),
        ]);
    }

    public function regenerateLicense(
        Request $request,
        string $licenseUuid,
        LicenseManagementService $licenseManagementService
    ): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $record = $licenseManagementService->resolveOwnedLicense($user->id, $licenseUuid);

        $oldKey = (string) $record->license_key;
        $newKey = License::generateKey();
        $meta = (array) ($record->meta ?? []);
        $meta['license_history'] = array_values(array_filter([
            ...((array) ($meta['license_history'] ?? [])),
            [
                'old_key' => $oldKey,
                'rotated_at' => now()->toISOString(),
            ],
        ]));

        $record->update([
            'license_key' => $newKey,
            'meta' => $meta,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'License key regenerated successfully.',
            'data' => $this->mapLicense($record->fresh(['product', 'product.engagement', 'product.activeSources', 'plan', 'apiKey.plan'])),
        ]);
    }

    public function summary(Request $request, LicenseManagementService $licenseManagementService): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'data' => $licenseManagementService->buildUserInsights($user),
        ]);
    }

    private function mapLicense(License $license): array
    {
        $product = $license->product;
        $plan = $license->plan;
        $apiKey = $license->apiKey;

        return [
            'uuid' => $license->uuid,
            'license_key' => $license->license_key,
            'type' => $license->type->value,
            'type_label' => $license->type->getLabel(),
            'status' => $this->determineStatus($license),
            'is_active' => $license->is_active,
            'usage_count' => $license->usage_count,
            'max_usage' => $license->max_usage,
            'remaining_usages' => $license->getRemainingUsages(),
            'expires_at' => $license->expires_at?->toIsoString(),
            'created_at' => $license->created_at?->toIsoString(),
            'next_renewal_at' => $license->expires_at?->toIsoString(),
            'product' => $this->mapProduct($product),
            'plan' => $plan ? $this->mapPlan($plan) : null,
            'download_sources' => $this->mapSources($product->activeSources),
            'api_key' => $apiKey ? $this->mapApiKey($apiKey) : null,
        ];
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

    private function mapProduct(Product $product): array
    {
        $engagement = $product->engagement;

        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'title' => $product->title,
            'short_description' => $product->short_description,
            'category' => $product->category,
            'type' => $product->type->value,
            'image' => $product->image,
            'version' => $product->version,
            'price' => (float) $product->price_major,
            'price_paise' => (int) $product->price,
            'requires_auth' => $product->requires_auth,
            'demo_url' => $product->demo_url,
            'documentation_url' => $product->documentation_url,
            'engagement' => [
                'downloads' => $engagement?->downloads ?? $product->downloads,
                'rating' => $engagement?->rating ?? $product->rating,
            ],
        ];
    }

    private function mapPlan($plan): array
    {
        return [
            'id' => $plan->id,
            'name' => $plan->name,
            'slug' => $plan->slug,
            'description' => $plan->description,
            'price' => $plan->price,
            'price_formatted' => $plan->getFormattedPrice(),
            'billing_cycle' => $plan->billing_cycle,
            'features' => $plan->features,
            'limits' => $plan->limits,
            'requests_per_month' => $plan->requests_per_month,
            'requests_per_day' => $plan->requests_per_day,
            'requests_per_minute' => $plan->requests_per_minute,
            'is_popular' => $plan->is_popular,
        ];
    }

    private function mapSources(Collection $sources): array
    {
        return $sources->map(fn (ProductSource $source) => [
            'id' => $source->id,
            'name' => $source->name,
            'description' => $source->description,
            'version' => $source->version,
            'file_name' => $source->file_name,
            'file_size' => $source->file_size,
            'file_size_formatted' => $source->getFileSizeFormatted(),
            'is_primary' => $source->is_primary,
            'provider' => $source->provider->value,
            'provider_label' => $source->provider->getLabel(),
        ])->toArray();
    }

    private function mapApiKey($apiKey): array
    {
        return [
            'id' => $apiKey->id,
            'key_prefix' => $apiKey->key_prefix,
            'name' => $apiKey->name,
            'is_active' => $apiKey->is_active,
            'requests_this_month' => $apiKey->requests_this_month,
            'requests_today' => $apiKey->requests_today,
            'last_used_at' => $apiKey->last_used_at?->toIsoString(),
            'expires_at' => $apiKey->expires_at?->toIsoString(),
            'environment' => $apiKey->environment,
            'domain_restriction' => $apiKey->domain_restriction,
            'allowed_domains' => $apiKey->allowed_domains ?? [],
            'ip_whitelist' => $apiKey->ip_whitelist ?? [],
            'plan' => $apiKey->plan ? [
                'requests_per_month' => $apiKey->plan->requests_per_month,
                'requests_per_day' => $apiKey->plan->requests_per_day,
            ] : null,
        ];
    }

    private function mapUpgradePlans(License $license): array
    {
        $plans = Plan::query()
            ->where('product_id', $license->product_id)
            ->where('is_active', true)
            ->when($license->plan_id, fn ($query) => $query->where('id', '!=', $license->plan_id))
            ->orderBy('sort_order')
            ->get();

        return $plans->map(fn (Plan $plan) => $this->mapPlan($plan))->all();
    }

    private function mapMatchedFrontends(Product $apiProduct): array
    {
        if (! in_array($apiProduct->type->value, ['api_service', 'api_referral'], true)) {
            return [];
        }

        return Product::query()
            ->where('type', 'downloadable')
            ->whereJsonContains('meta->api_matches', $apiProduct->slug)
            ->orderByDesc('featured')
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get()
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'slug' => $product->slug,
                'title' => $product->title,
                'short_description' => $product->short_description,
                'image' => $product->image,
                'demo_url' => $product->demo_url,
                'documentation_url' => $product->documentation_url,
                'price' => (float) $product->price_major,
                'price_paise' => (int) $product->price,
                'version' => $product->version,
            ])
            ->all();
    }

    private function featureFlags(): array
    {
        return [
            'downloads_enabled' => true,
            'api_access_enabled' => true,
            'licensing_enabled' => true,
        ];
    }

}
