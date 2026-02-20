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

class DashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'client@mintreu.com')->first();

        if (! $user) {
            return;
        }

        // Keep dashboard demo aligned with production catalog: no legacy/fake API products.
        $this->seedApiSubscription(
            user: $user,
            productSlug: 'shopcore-commerce-api',
            preferredPlanSlug: 'growth',
            requestsThisMonth: 1200,
            requestsToday: 95,
            spaceName: 'Velori Demo Space',
            website: 'https://demo-velori-boutique.mintreu.com',
            topEndpoint: 'POST /api/orders'
        );

        $this->seedApiSubscription(
            user: $user,
            productSlug: 'helpdesk-support-api',
            preferredPlanSlug: 'starter',
            requestsThisMonth: 18300,
            requestsToday: 750,
            spaceName: 'Tixora Demo Space',
            website: 'https://demo-tixora-support.mintreu.com',
            topEndpoint: 'POST /api/tickets'
        );
    }

    private function seedApiSubscription(
        User $user,
        string $productSlug,
        string $preferredPlanSlug,
        int $requestsThisMonth,
        int $requestsToday,
        string $spaceName,
        string $website,
        string $topEndpoint
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
                'usage_count' => 0,
                'max_usage' => null,
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
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
                'name' => $product->title.' API Key',
                'environment' => 'prod',
                'allowed_domains' => [parse_url($website, PHP_URL_HOST)],
                'ip_whitelist' => [],
                'is_active' => true,
                'expires_at' => now()->addMonths(12),
            ]);
        } else {
            $apiKey->update([
                'plan_id' => $plan->id,
                'is_active' => true,
                'expires_at' => now()->addMonths(12),
                'allowed_domains' => [parse_url($website, PHP_URL_HOST)],
            ]);
        }

        $apiKey->update([
            'requests_this_month' => $requestsThisMonth,
            'requests_today' => $requestsToday,
        ]);

        ApiSpace::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'api_key_id' => $apiKey->id,
                'website' => $website,
            ],
            [
                'product_id' => $product->id,
                'name' => $spaceName,
                'environment' => 'prod',
                'status' => 'active',
                'requests_this_month' => max(1, (int) floor($requestsThisMonth * 0.7)),
                'requests_today' => max(1, (int) floor($requestsToday * 0.6)),
                'last_request_at' => now()->subMinutes(8),
                'config' => [
                    'seeded' => true,
                ],
                'insights' => [
                    'avg_latency_ms' => 110,
                    'error_rate_percent' => 0.4,
                    'top_endpoint' => $topEndpoint,
                ],
            ]
        );
    }
}
