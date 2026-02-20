<?php

namespace Database\Seeders;

use App\Enums\LicenseType;
use App\Models\Api\ApiKey;
use App\Models\Api\ApiSpace;
use App\Models\Product;
use App\Models\Products\Plan;
use App\Models\User;
use App\Services\Licensing\LicenseManagementService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OfficialUserSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->updateOrCreate(
            ['email' => 'owner@mintreu.test'],
            [
                'name' => 'Mintreu Owner',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'owner@mintreu.com'],
            [
                'name' => 'Mintreu Owner',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $manager = User::query()->updateOrCreate(
            ['email' => 'growth@mintreu.test'],
            [
                'name' => 'Growth Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $ops = User::query()->updateOrCreate(
            ['email' => 'ops@mintreu.test'],
            [
                'name' => 'Operations Lead',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->seedApiSubscription(
            user: $owner,
            productSlug: 'shopcore-commerce-api',
            preferredPlanSlug: 'growth',
            spaces: [
                ['name' => 'Velori Live', 'website' => 'https://demo-velori-boutique.mintreu.com', 'environment' => 'prod', 'status' => 'active', 'requests_this_month' => 125430, 'requests_today' => 4201, 'avg_latency_ms' => 112, 'error_rate_percent' => 0.41, 'top_endpoint' => 'POST /api/orders'],
                ['name' => 'Velori Staging', 'website' => 'https://demo-velori-boutique.mintreu.test', 'environment' => 'staging', 'status' => 'active', 'requests_this_month' => 16320, 'requests_today' => 530, 'avg_latency_ms' => 136, 'error_rate_percent' => 0.74, 'top_endpoint' => 'GET /api/products'],
                ['name' => 'Lunera Production', 'website' => 'https://demo-lunera-divas.mintreu.com', 'environment' => 'prod', 'status' => 'active', 'requests_this_month' => 84210, 'requests_today' => 3180, 'avg_latency_ms' => 121, 'error_rate_percent' => 0.56, 'top_endpoint' => 'GET /api/products'],
                ['name' => 'Havena Production', 'website' => 'https://demo-havena-home.mintreu.com', 'environment' => 'prod', 'status' => 'active', 'requests_this_month' => 79440, 'requests_today' => 2755, 'avg_latency_ms' => 128, 'error_rate_percent' => 0.63, 'top_endpoint' => 'POST /api/cart'],
            ],
            keyUsage: [
                'requests_this_month' => 305400,
                'requests_today' => 10666,
                'last_used_at' => now()->subMinutes(12),
            ],
            expiresDays: 27
        );

        $this->seedApiSubscription(
            user: $owner,
            productSlug: 'helpdesk-support-api',
            preferredPlanSlug: 'pro',
            spaces: [
                ['name' => 'Tixora Live', 'website' => 'https://demo-tixora-support.mintreu.com', 'environment' => 'prod', 'status' => 'active', 'requests_this_month' => 52870, 'requests_today' => 1790, 'avg_latency_ms' => 97, 'error_rate_percent' => 0.36, 'top_endpoint' => 'POST /api/tickets'],
                ['name' => 'Copira AI Desk', 'website' => 'https://demo-copira-ai-desk.mintreu.com', 'environment' => 'prod', 'status' => 'active', 'requests_this_month' => 41110, 'requests_today' => 1340, 'avg_latency_ms' => 89, 'error_rate_percent' => 0.28, 'top_endpoint' => 'POST /api/assist/reply'],
            ],
            keyUsage: [
                'requests_this_month' => 93980,
                'requests_today' => 3130,
                'last_used_at' => now()->subMinutes(5),
            ],
            expiresDays: 5
        );

        $this->seedApiSubscription(
            user: $manager,
            productSlug: 'shopcore-commerce-api',
            preferredPlanSlug: 'launch',
            spaces: [
                ['name' => 'Playro Live', 'website' => 'https://demo-playro-kids.mintreu.com', 'environment' => 'prod', 'status' => 'active', 'requests_this_month' => 29420, 'requests_today' => 960, 'avg_latency_ms' => 142, 'error_rate_percent' => 0.88, 'top_endpoint' => 'GET /api/categories'],
                ['name' => 'Playro QA', 'website' => 'https://demo-playro-kids.mintreu.test', 'environment' => 'staging', 'status' => 'paused', 'requests_this_month' => 6110, 'requests_today' => 0, 'avg_latency_ms' => 0, 'error_rate_percent' => 0, 'top_endpoint' => null],
            ],
            keyUsage: [
                'requests_this_month' => 35530,
                'requests_today' => 960,
                'last_used_at' => now()->subHours(2),
            ],
            expiresDays: 14
        );

        $this->seedApiSubscription(
            user: $ops,
            productSlug: 'helpdesk-support-api',
            preferredPlanSlug: 'starter',
            spaces: [
                ['name' => 'Cartrio Desk', 'website' => 'https://demo-cartrio-support-desk.mintreu.com', 'environment' => 'prod', 'status' => 'active', 'requests_this_month' => 16780, 'requests_today' => 540, 'avg_latency_ms' => 105, 'error_rate_percent' => 0.59, 'top_endpoint' => 'POST /api/messages'],
            ],
            keyUsage: [
                'requests_this_month' => 16780,
                'requests_today' => 540,
                'last_used_at' => now()->subMinutes(30),
            ],
            expiresDays: 3
        );

        $this->seedDownloadLicense($owner, 'velori-boutique', 4, now()->subDays(11), now()->addMonths(10));
        $this->seedDownloadLicense($owner, 'havena-home', 2, now()->subDays(8), now()->addMonths(9));
        $this->seedDownloadLicense($owner, 'tixora-support', 1, now()->subDays(2), now()->addMonths(11));
        $this->seedDownloadLicense($manager, 'playro-kids', 1, now()->subDays(6), now()->addMonths(7));
    }

    /**
     * @param  array<int, array<string, mixed>>  $spaces
     * @param  array{requests_this_month?: int, requests_today?: int, last_used_at?: \Illuminate\Support\Carbon|\Carbon\CarbonInterface|string|null}  $keyUsage
     */
    private function seedApiSubscription(
        User $user,
        string $productSlug,
        string $preferredPlanSlug,
        array $spaces,
        array $keyUsage = [],
        int $expiresDays = 30
    ): void {
        $product = Product::query()->where('slug', $productSlug)->first();
        if (! $product) {
            return;
        }

        $plan = Plan::query()
            ->where('product_id', $product->id)
            ->where('slug', $preferredPlanSlug)
            ->where('is_active', true)
            ->first();

        if (! $plan) {
            $plan = Plan::query()
                ->where('product_id', $product->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->first();
        }

        if (! $plan) {
            return;
        }

        $license = app(LicenseManagementService::class)->upsertSubscriptionLicense(
            user: $user,
            product: $product,
            plan: $plan,
            attributes: [
                'is_active' => true,
                'expires_at' => now()->addDays($expiresDays),
                'usage_count' => 0,
                'max_usage' => null,
                'meta' => [
                    'seeded_by' => self::class,
                    'billing_model' => 'overall_plus_site_addon',
                ],
            ]
        );

        $apiKey = $license->apiKey;
        if (! $apiKey) {
            $keyData = ApiKey::generateKey('sk_live');
            $apiKey = ApiKey::query()->create([
                'product_id' => $product->id,
                'license_id' => $license->id,
                'plan_id' => $plan->id,
                'user_id' => $user->id,
                'key_hash' => $keyData['hash'],
                'key_prefix' => $keyData['prefix'],
                'name' => $product->title.' - '.$plan->name,
                'environment' => 'prod',
                'allowed_domains' => [],
                'ip_whitelist' => [],
                'is_active' => true,
                'expires_at' => $license->expires_at,
            ]);
        } else {
            $apiKey->update([
                'plan_id' => $plan->id,
                'is_active' => true,
                'expires_at' => $license->expires_at,
            ]);
        }

        $apiKey->update([
            'requests_this_month' => (int) ($keyUsage['requests_this_month'] ?? 0),
            'requests_today' => (int) ($keyUsage['requests_today'] ?? 0),
            'last_used_at' => $keyUsage['last_used_at'] ?? null,
            'allowed_domains' => collect($spaces)->pluck('website')
                ->map(fn ($url) => parse_url((string) $url, PHP_URL_HOST))
                ->filter()
                ->values()
                ->all(),
        ]);

        foreach ($spaces as $space) {
            ApiSpace::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'api_key_id' => $apiKey->id,
                    'website' => $space['website'],
                ],
                [
                    'product_id' => $product->id,
                    'name' => $space['name'],
                    'environment' => $space['environment'],
                    'status' => (string) ($space['status'] ?? 'active'),
                    'requests_this_month' => (int) ($space['requests_this_month'] ?? 0),
                    'requests_today' => (int) ($space['requests_today'] ?? 0),
                    'last_request_at' => isset($space['requests_today']) && (int) $space['requests_today'] > 0 ? now()->subMinutes(random_int(3, 180)) : null,
                    'config' => [
                        'seeded' => true,
                    ],
                    'insights' => [
                        'avg_latency_ms' => (float) ($space['avg_latency_ms'] ?? 0),
                        'error_rate_percent' => (float) ($space['error_rate_percent'] ?? 0),
                        'top_endpoint' => $space['top_endpoint'] ?? null,
                    ],
                ]
            );
        }
    }

    private function seedDownloadLicense(User $user, string $productSlug, int $usageCount = 0, $firstUsedAt = null, $expiresAt = null): void
    {
        $product = Product::query()->where('slug', $productSlug)->first();
        if (! $product) {
            return;
        }

        app(LicenseManagementService::class)->upsertProductLicense(
            user: $user,
            product: $product,
            type: LicenseType::CommercialSingle,
            attributes: [
                'is_active' => true,
                'usage_count' => $usageCount,
                'max_usage' => null,
                'first_used_at' => $firstUsedAt,
                'last_used_at' => $firstUsedAt ? now()->subHours(random_int(2, 48)) : null,
                'expires_at' => $expiresAt ?? now()->addYears(1),
                'meta' => [
                    'seeded_by' => self::class,
                    'download_access' => true,
                ],
            ]
        );
    }
}
