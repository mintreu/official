<?php

namespace Database\Seeders;

use App\Models\Api\ApiKey;
use App\Models\Api\ApiSpace;
use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\Plan;
use Illuminate\Database\Seeder;

class LegacyDemoCleanupSeeder extends Seeder
{
    public function run(): void
    {
        $legacyProductSlugs = [
            'mintreu-data-api',
            'mintreu-notify-api',
            'nextjs-saas-boilerplate',
            'premium-ui-kit-figma',
            'wordpress-shop-manager-pro',
            'vue3-dashboard-template',
        ];

        $legacyProductIds = Product::query()
            ->whereIn('slug', $legacyProductSlugs)
            ->pluck('id');

        if ($legacyProductIds->isEmpty()) {
            return;
        }

        $licenseIds = License::query()
            ->whereIn('product_id', $legacyProductIds)
            ->pluck('id');

        ApiSpace::query()
            ->whereIn('product_id', $legacyProductIds)
            ->delete();

        if ($licenseIds->isNotEmpty()) {
            ApiSpace::query()
                ->whereIn('api_key_id', ApiKey::query()->whereIn('license_id', $licenseIds)->pluck('id'))
                ->delete();
        }

        ApiKey::query()
            ->whereIn('product_id', $legacyProductIds)
            ->delete();

        if ($licenseIds->isNotEmpty()) {
            ApiKey::query()
                ->whereIn('license_id', $licenseIds)
                ->delete();
        }

        Plan::query()
            ->whereIn('product_id', $legacyProductIds)
            ->delete();

        License::query()
            ->whereIn('product_id', $legacyProductIds)
            ->delete();

        Product::query()
            ->whereIn('id', $legacyProductIds)
            ->delete();
    }
}
