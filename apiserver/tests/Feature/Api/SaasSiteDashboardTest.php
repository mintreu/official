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

class SaasSiteDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_their_licensed_sites(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'type' => ProductType::ApiService,
            'default_license' => LicenseType::ApiSubscription,
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
            'site_slug' => 'velori-demo',
            'site_name' => 'Velori Demo',
            'site_uuid' => '11111111-1111-1111-1111-111111111111',
            'site_license_key' => 'msk_test',
            'status' => 'active',
            'provisioned_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/saas/sites');
        $response->assertOk();
        $response->assertJsonPath('data.0.source_project', 'shopcore-commerce-api');
        $response->assertJsonPath('data.0.site.slug', 'velori-demo');
    }

    public function test_authenticated_user_gets_overview_insights_from_real_events(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'type' => ProductType::ApiService,
            'default_license' => LicenseType::ApiSubscription,
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
            'site_slug' => 'velori-prod',
            'site_name' => 'Velori Prod',
            'site_uuid' => '22222222-2222-2222-2222-222222222222',
            'site_license_key' => 'msk_prod',
            'status' => 'active',
            'provisioned_at' => now(),
        ]);

        SiteStatEvent::query()->create([
            'source_project' => 'shopcore-commerce-api',
            'vendor_id' => 901,
            'site_id' => 77,
            'site_uuid' => '22222222-2222-2222-2222-222222222222',
            'site_slug' => 'velori-prod',
            'metrics' => [
                'orders_count' => 7,
                'new_users_count' => 3,
                'revenue_paise' => 99000,
                'requests_count' => 150,
                'errors_count' => 2,
            ],
            'payload' => [
                'machine' => ['host' => 'srv-velori-01', 'os' => 'linux'],
                'runtime' => ['node' => '20.10', 'nuxt' => '3.15'],
            ],
            'received_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/saas/insights/overview?minutes=1440');
        $response->assertOk();
        $response->assertJsonPath('data.totals.orders_count', 7);
        $response->assertJsonPath('data.totals.new_users_count', 3);
        $response->assertJsonPath('data.totals.revenue_paise', 99000);
        $response->assertJsonPath('data.totals.requests_count', 150);
        $response->assertJsonPath('data.totals.errors_count', 2);
    }
}
