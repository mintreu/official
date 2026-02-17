<?php

namespace Database\Seeders;

use App\Enums\LicenseType;
use App\Enums\ProductType;
use App\Models\Api\ApiKey;
use App\Models\Licensing\License;
use App\Models\Products\Plan;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'client@mintreu.com')->first();

        if (! $user) {
            return;
        }

        $downloadProduct = Product::where('slug', 'nextjs-saas-boilerplate')->first();

        if ($downloadProduct) {
            License::updateOrCreate([
                'product_id' => $downloadProduct->id,
                'user_id' => $user->id,
            ], [
                'license_key' => License::generateKey(),
                'type' => LicenseType::CommercialSingle,
                'usage_count' => 2,
                'max_usage' => 5,
                'expires_at' => now()->addDays(180),
                'is_active' => true,
            ]);
        }

        $apiProduct = Product::firstOrCreate([
            'slug' => 'mintreu-data-api',
        ], [
            'title' => 'Mintreu Data API',
            'short_description' => 'Secure, rate-limited API that powers Mintreu product and licensing insights.',
            'content' => '<p>Unlock Mintreu metadata, licensing status, and download orchestration through a single API.</p>',
            'price' => 0,
            'category' => 'API',
            'type' => ProductType::ApiService,
            'demo_url' => 'https://docs.mintreu.com/data-api',
            'documentation_url' => 'https://docs.mintreu.com/data-api',
            'version' => '1.0.0',
            'downloads' => 0,
            'rating' => 4.9,
            'status' => 'Published',
            'featured' => false,
            'requires_auth' => true,
            'default_license' => LicenseType::ApiSubscription,
            'meta' => ['public' => true],
            'api_config' => ['supports_webhooks' => true],
        ]);

        $professionalPlan = Plan::updateOrCreate([
            'product_id' => $apiProduct->id,
            'slug' => 'professional',
        ], [
            'name' => 'Professional',
            'description' => 'Monthly plan with high-throughput API access.',
            'price_cents' => 9900,
            'billing_cycle' => 'monthly',
            'requests_per_month' => 120000,
            'requests_per_day' => 5000,
            'requests_per_minute' => 200,
            'features' => ['Access to product metadata', 'Audit logs', 'Email + chat support'],
            'limits' => ['domains' => 3],
            'sort_order' => 1,
            'is_popular' => true,
            'is_active' => true,
        ]);

        $apiLicense = License::updateOrCreate([
            'product_id' => $apiProduct->id,
            'user_id' => $user->id,
        ], [
            'plan_id' => $professionalPlan->id,
            'license_key' => License::generateKey(),
            'type' => LicenseType::ApiSubscription,
            'usage_count' => 1200,
            'max_usage' => null,
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ]);

        $keyData = ApiKey::generateKey('sk_live');

        ApiKey::updateOrCreate([
            'license_id' => $apiLicense->id,
        ], [
            'product_id' => $apiProduct->id,
            'plan_id' => $professionalPlan->id,
            'user_id' => $user->id,
            'key_hash' => $keyData['hash'],
            'key_prefix' => $keyData['prefix'],
            'name' => 'Primary API Key',
            'environment' => 'prod',
            'requests_this_month' => 1200,
            'requests_today' => 95,
            'is_active' => true,
            'expires_at' => now()->addMonths(12),
        ]);
    }
}
