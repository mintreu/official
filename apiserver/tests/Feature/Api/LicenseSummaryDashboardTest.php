<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Database\Seeders\OfficialUserSeeder;
use Database\Seeders\RevenuePortfolioSeeder;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LicenseSummaryDashboardTest extends TestCase
{
    public function test_summary_returns_project_insights_for_dashboard(): void
    {
        if (! Schema::hasTable('product_engagements')) {
            $this->markTestSkipped('product_engagements table is not available in testing schema.');
        }

        $this->seed([
            RevenuePortfolioSeeder::class,
            OfficialUserSeeder::class,
        ]);

        $owner = User::query()->where('email', 'owner@mintreu.test')->firstOrFail();
        Sanctum::actingAs($owner);

        $response = $this->getJson('/api/licenses/summary');
        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'totals' => [
                    'licenses',
                    'active_licenses',
                    'active_api_subscriptions',
                    'active_api_projects',
                    'expiring_soon',
                    'spaces',
                ],
                'site_billing',
                'project_insights',
                'upcoming_renewals',
            ],
        ]);

        $projectSlugs = collect($response->json('data.project_insights', []))
            ->pluck('product_slug')
            ->filter()
            ->values()
            ->all();

        $this->assertContains('shopcore-commerce-api', $projectSlugs);
        $this->assertContains('helpdesk-support-api', $projectSlugs);

        $totals = $response->json('data.totals');
        $this->assertGreaterThanOrEqual(2, (int) ($totals['active_api_projects'] ?? 0));
        $this->assertGreaterThan(0, (int) ($totals['spaces'] ?? 0));
    }
}
