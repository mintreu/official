<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class LocalRevenuePortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([RevenuePortfolioSeeder::class]);

        Product::query()
            ->whereNotNull('demo_url')
            ->get()
            ->each(function (Product $product): void {
                $demo = $this->toLocalUrl($product->demo_url);
                $docs = $this->toLocalUrl($product->documentation_url);
                $apiConfig = $product->api_config;

                if (is_array($apiConfig) && isset($apiConfig['base_url'])) {
                    $apiConfig['base_url'] = $this->toLocalUrl((string) $apiConfig['base_url']);
                }

                $product->update([
                    'demo_url' => $demo,
                    'documentation_url' => $docs,
                    'api_config' => $apiConfig,
                ]);
            });
    }

    private function toLocalUrl(?string $url): ?string
    {
        if (! $url) {
            return $url;
        }

        return str_replace(
            ['mintreu.com', 'shopcore.mintreu.com', 'helpdesk-api.mintreu.com'],
            ['mintreu.test', 'shopcore.test', 'helpdesk.test'],
            $url
        );
    }
}

