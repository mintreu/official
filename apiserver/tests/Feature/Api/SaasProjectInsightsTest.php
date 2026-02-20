<?php

namespace Tests\Feature\Api;

use App\Enums\LicenseType;
use App\Enums\ProductType;
use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Saas\LicensedSite;
use App\Models\Saas\SiteStatEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SaasProjectInsightsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_project_insight_cards(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'type' => ProductType::ApiService,
            'default_license' => LicenseType::ApiSubscription,
            'slug' => 'shopcore-commerce-api',
        ]);
        $license = License::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'type' => LicenseType::ApiSubscription,
            'is_active' => true,
        ]);

        LicensedSite::query()->create([
            'user_id' => $user->id,
            'license_id' => $license->id,
            'product_id' => $product->id,
            'source_project' => 'shopcore-commerce-api',
            'vendor_id' => 101,
            'site_slug' => 'velori-live',
            'site_name' => 'Velori Live',
            'site_uuid' => '33333333-3333-3333-3333-333333333333',
            'site_license_key' => 'msk_live',
            'status' => 'active',
            'provisioned_at' => now(),
        ]);

        SiteStatEvent::query()->create([
            'source_project' => 'shopcore-commerce-api',
            'vendor_id' => 101,
            'site_id' => 81,
            'site_uuid' => '33333333-3333-3333-3333-333333333333',
            'site_slug' => 'velori-live',
            'metrics' => [
                'orders_count' => 9,
                'new_users_count' => 2,
                'revenue_paise' => 149000,
                'requests_count' => 220,
                'errors_count' => 1,
            ],
            'payload' => [],
            'received_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $listResponse = $this->getJson('/api/saas/projects');
        $listResponse->assertOk();
        $listResponse->assertJsonPath('data.0.project', 'shopcore-commerce-api');
        $listResponse->assertJsonPath('data.0.totals.orders_count', 9);

        $detailResponse = $this->getJson('/api/saas/projects/shopcore-commerce-api');
        $detailResponse->assertOk();
        $detailResponse->assertJsonPath('data.project', 'shopcore-commerce-api');
        $detailResponse->assertJsonPath('data.totals.requests_count', 220);
    }
}
