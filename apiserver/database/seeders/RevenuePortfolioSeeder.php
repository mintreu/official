<?php

namespace Database\Seeders;

use App\Casts\PublishableStatusCast;
use App\Enums\LicenseType;
use App\Enums\ProductType;
use App\Enums\SourceProvider;
use App\Models\Product;
use App\Models\Products\Plan;
use App\Models\Products\ProductEngagement;
use App\Models\Products\ProductSource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class RevenuePortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $commerceFrontends = [
            ['slug' => 'velori-boutique', 'title' => 'Velori Boutique', 'segment' => 'boutique-fashion', 'variant' => 'client_velori', 'price' => 199, 'allowed' => ['women-fashion', 'ethnic-wear', 'boutique']],
            ['slug' => 'havena-home', 'title' => 'Havena Home', 'segment' => 'furniture-home-decor', 'variant' => 'client_havena', 'price' => 239, 'allowed' => ['furniture', 'home-decor', 'lighting']],
            ['slug' => 'lunera-divas', 'title' => 'Lunera Divas', 'segment' => 'women-lifestyle', 'variant' => 'client_lunera', 'price' => 219, 'allowed' => ['women-fashion', 'beauty', 'accessories']],
            ['slug' => 'playro-kids', 'title' => 'Playro Kids', 'segment' => 'toys-kids', 'variant' => 'client_playro', 'price' => 189, 'allowed' => ['toys', 'kids-learning', 'baby-products']],
            ['slug' => 'ardent-men', 'title' => 'Ardent Men', 'segment' => 'menswear', 'variant' => 'client_ardent', 'price' => 199, 'allowed' => ['menswear', 'footwear', 'grooming']],
            ['slug' => 'petalyn-beauty', 'title' => 'Petalyn Beauty', 'segment' => 'beauty-personal-care', 'variant' => 'client_petalyn', 'price' => 209, 'allowed' => ['skincare', 'makeup', 'haircare']],
            ['slug' => 'nomado-gear', 'title' => 'Nomado Gear', 'segment' => 'travel-gear', 'variant' => 'client_nomado', 'price' => 199, 'allowed' => ['luggage', 'travel-accessories', 'outdoor-gear']],
            ['slug' => 'ironoak-fitness', 'title' => 'Ironoak Fitness', 'segment' => 'fitness-sports', 'variant' => 'client_ironoak', 'price' => 229, 'allowed' => ['gym-gear', 'sportswear', 'supplements']],
            ['slug' => 'culinix-gourmet', 'title' => 'Culinix Gourmet', 'segment' => 'food-gourmet', 'variant' => 'client_culinix', 'price' => 209, 'allowed' => ['snacks', 'gourmet-food', 'gift-hampers']],
            ['slug' => 'booklore-supplies', 'title' => 'Booklore Supplies', 'segment' => 'books-stationery', 'variant' => 'client_booklore', 'price' => 179, 'allowed' => ['books', 'stationery', 'office-supplies']],
            ['slug' => 'toyfable-play', 'title' => 'Toyfable Play', 'segment' => 'educational-toys', 'variant' => 'client_toyfable', 'price' => 189, 'allowed' => ['learning-toys', 'board-games', 'puzzles']],
            ['slug' => 'blazeauto-parts', 'title' => 'Blazeauto Parts', 'segment' => 'auto-parts', 'variant' => 'client_blazeauto', 'price' => 249, 'allowed' => ['car-accessories', 'bike-accessories', 'spare-parts']],
            ['slug' => 'greenary-organics', 'title' => 'Greenary Organics', 'segment' => 'organic-lifestyle', 'variant' => 'client_greenary', 'price' => 199, 'allowed' => ['organic-food', 'natural-care', 'eco-home']],
            ['slug' => 'mediora-wellness', 'title' => 'Mediora Wellness', 'segment' => 'wellness-health', 'variant' => 'client_mediora', 'price' => 229, 'allowed' => ['wellness', 'supplements', 'health-devices']],
            ['slug' => 'techvora-gadgets', 'title' => 'Techvora Gadgets', 'segment' => 'electronics-gadgets', 'variant' => 'client_techvora', 'price' => 259, 'allowed' => ['mobiles', 'smart-devices', 'accessories']],
            ['slug' => 'craftique-handmade', 'title' => 'Craftique Handmade', 'segment' => 'handmade-artisan', 'variant' => 'client_craftique', 'price' => 209, 'allowed' => ['handmade', 'artisanal', 'gift-items']],
            ['slug' => 'prismarugs-decor', 'title' => 'Prismarugs Decor', 'segment' => 'home-textiles', 'variant' => 'client_prismarugs', 'price' => 219, 'allowed' => ['rugs', 'curtains', 'home-textiles']],
            ['slug' => 'urbanza-footwear', 'title' => 'Urbanza Footwear', 'segment' => 'footwear', 'variant' => 'client_urbanza', 'price' => 199, 'allowed' => ['sneakers', 'formal-shoes', 'sandals']],
            ['slug' => 'jewelora-atelier', 'title' => 'Jewelora Atelier', 'segment' => 'jewelry', 'variant' => 'client_jewelora', 'price' => 269, 'allowed' => ['gold-jewelry', 'silver-jewelry', 'fashion-jewelry']],
            ['slug' => 'babynest-essentials', 'title' => 'Babynest Essentials', 'segment' => 'baby-care', 'variant' => 'client_babynest', 'price' => 199, 'allowed' => ['baby-care', 'feeding', 'baby-fashion']],
        ];

        $helpdeskFrontends = [
            ['slug' => 'tixora-support', 'title' => 'Tixora Support', 'focus' => 'ticket-operations', 'variant' => 'helpdesk_tixora_ticket', 'price' => 179, 'offer_type' => 'ticket', 'allowed' => ['technical-support', 'billing-support', 'account-support']],
            ['slug' => 'copira-ai-desk', 'title' => 'Copira AI Desk', 'focus' => 'ai-assisted-support', 'variant' => 'helpdesk_copira_ai', 'price' => 259, 'offer_type' => 'ai_assisted', 'allowed' => ['priority-support', 'technical-support', 'ops-support']],
            ['slug' => 'cartrio-support-desk', 'title' => 'Cartrio Support Desk', 'focus' => 'ecommerce-support', 'variant' => 'helpdesk_cartrio_message', 'price' => 199, 'offer_type' => 'message', 'allowed' => ['order-support', 'returns-support', 'refund-support']],
            ['slug' => 'relaygo-livechat', 'title' => 'Relaygo Livechat', 'focus' => 'live-chat-support', 'variant' => 'helpdesk_relaygo_livechat', 'price' => 229, 'offer_type' => 'live_chat', 'allowed' => ['live-support', 'pre-sales-support', 'customer-support']],
            ['slug' => 'pulseqa-service-desk', 'title' => 'PulseQA Service Desk', 'focus' => 'qa-governed-support', 'variant' => 'helpdesk_pulseqa_ticket', 'price' => 209, 'offer_type' => 'ticket', 'allowed' => ['quality-support', 'compliance-support', 'technical-support']],
            ['slug' => 'claimora-resolution-hub', 'title' => 'Claimora Resolution Hub', 'focus' => 'claim-resolution', 'variant' => 'helpdesk_claimora_resolution', 'price' => 219, 'offer_type' => 'ticket', 'allowed' => ['claims-support', 'dispute-support', 'policy-support']],
            ['slug' => 'fincue-billing-desk', 'title' => 'Fincue Billing Desk', 'focus' => 'billing-support', 'variant' => 'helpdesk_fincue_billing', 'price' => 199, 'offer_type' => 'message', 'allowed' => ['billing-support', 'invoice-support', 'payment-support']],
            ['slug' => 'vendesk-merchant-care', 'title' => 'Vendesk Merchant Care', 'focus' => 'merchant-support', 'variant' => 'helpdesk_vendesk_merchant', 'price' => 219, 'offer_type' => 'message', 'allowed' => ['merchant-support', 'onboarding-support', 'operations-support']],
            ['slug' => 'hostora-client-desk', 'title' => 'Hostora Client Desk', 'focus' => 'saas-incident-support', 'variant' => 'helpdesk_hostora_incident', 'price' => 209, 'offer_type' => 'ticket', 'allowed' => ['incident-support', 'account-support', 'technical-support']],
            ['slug' => 'medisign-patient-support', 'title' => 'Medisign Patient Support', 'focus' => 'healthcare-support', 'variant' => 'helpdesk_medisign_patient', 'price' => 229, 'offer_type' => 'ticket', 'allowed' => ['patient-support', 'appointment-support', 'billing-support']],
        ];

        $allowedProductSlugs = array_merge(
            ['shopcore-commerce-api', 'helpdesk-support-api'],
            array_column($commerceFrontends, 'slug'),
            array_column($helpdeskFrontends, 'slug'),
        );

        Product::query()->whereNotIn('slug', $allowedProductSlugs)->delete();

        $shopcoreApi = Product::query()->updateOrCreate(
            ['slug' => 'shopcore-commerce-api'],
            [
                'title' => 'PulseCart Commerce Cloud API',
                'short_description' => 'Managed commerce API subscription powering 20 niche Nuxt business products.',
                'description' => 'One hosted ecommerce API for catalog, cart, checkout, and order operations across personalized commerce frontends.',
                'content' => '<h2>API Product</h2><p>Hosted API access only. Backend source is not distributed.</p><h2>Compatible Frontends</h2><p>20 dedicated commerce Nuxt products.</p>',
                'image' => 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?w=1200',
                'price' => 0,
                'category' => 'Commerce API Subscription',
                'type' => ProductType::ApiService,
                'demo_url' => 'https://try-shopcore.mintreu.com',
                'documentation_url' => 'https://docs.mintreu.com/shopcore',
                'version' => '3.1.0',
                'downloads' => 0,
                'rating' => 4.9,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'requires_auth' => true,
                'default_license' => LicenseType::ApiSubscription,
                'meta' => [
                    'project' => 'shopcore',
                    'product_kind' => 'api_subscription',
                    'frontend_products' => array_column($commerceFrontends, 'slug'),
                    'catalog_size' => count($commerceFrontends),
                ],
                'api_config' => [
                    'base_url' => 'https://shopcore.mintreu.com/api/v1',
                    'auth' => 'bearer',
                    'supports_webhooks' => true,
                    'supports_idempotency' => true,
                ],
            ]
        );

        $helpdeskApi = Product::query()->updateOrCreate(
            ['slug' => 'helpdesk-support-api'],
            [
                'title' => 'HelpdeskFlow Support API',
                'short_description' => 'Managed support API subscription powering 10 specialized Nuxt support products.',
                'description' => 'One hosted support API for ticketing, replies, live support workflows, and escalation flows.',
                'content' => '<h2>API Product</h2><p>Hosted API access only. Backend source is not distributed.</p><h2>Compatible Frontends</h2><p>10 dedicated support Nuxt products.</p>',
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200',
                'price' => 0,
                'category' => 'Support API Subscription',
                'type' => ProductType::ApiService,
                'demo_url' => 'https://try-helpdesk.mintreu.com',
                'documentation_url' => 'https://docs.mintreu.com/helpdesk',
                'version' => '1.2.0',
                'downloads' => 0,
                'rating' => 4.9,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'requires_auth' => true,
                'default_license' => LicenseType::ApiSubscription,
                'meta' => [
                    'project' => 'helpdesk',
                    'product_kind' => 'api_subscription',
                    'frontend_products' => array_column($helpdeskFrontends, 'slug'),
                    'catalog_size' => count($helpdeskFrontends),
                ],
                'api_config' => [
                    'base_url' => 'https://helpdesk-api.mintreu.com/v1',
                    'auth' => 'bearer',
                    'supports_webhooks' => true,
                    'supports_idempotency' => true,
                ],
            ]
        );

        $this->upsertPlans($shopcoreApi->id, [
            ['slug' => 'launch', 'name' => 'Launch', 'description' => 'For one live store.', 'price_cents' => 9900, 'billing_cycle' => 'monthly', 'requests_per_month' => 120000, 'requests_per_day' => 6000, 'requests_per_minute' => 120, 'features' => ['Catalog + checkout API', '1 site', '1 API key'], 'limits' => ['sites' => 1], 'sort_order' => 1, 'is_popular' => false, 'is_active' => true],
            ['slug' => 'growth', 'name' => 'Growth', 'description' => 'For multi-product operation.', 'price_cents' => 24900, 'billing_cycle' => 'monthly', 'requests_per_month' => 500000, 'requests_per_day' => 25000, 'requests_per_minute' => 360, 'features' => ['Everything in Launch', '3 sites', '3 API keys'], 'limits' => ['sites' => 3], 'sort_order' => 2, 'is_popular' => true, 'is_active' => true],
            ['slug' => 'scale', 'name' => 'Scale', 'description' => 'For agencies and multi-brand teams.', 'price_cents' => 59900, 'billing_cycle' => 'monthly', 'requests_per_month' => 2000000, 'requests_per_day' => 100000, 'requests_per_minute' => 1200, 'features' => ['Everything in Growth', '10 sites', '10 API keys'], 'limits' => ['sites' => 10], 'sort_order' => 3, 'is_popular' => false, 'is_active' => true],
        ]);

        $this->upsertPlans($helpdeskApi->id, [
            ['slug' => 'starter', 'name' => 'Starter', 'description' => 'For one support team.', 'price_cents' => 6900, 'billing_cycle' => 'monthly', 'requests_per_month' => 100000, 'requests_per_day' => 5000, 'requests_per_minute' => 100, 'features' => ['Ticket API', '1 site', '1 API key'], 'limits' => ['sites' => 1], 'sort_order' => 1, 'is_popular' => false, 'is_active' => true],
            ['slug' => 'pro', 'name' => 'Pro', 'description' => 'For growing support operations.', 'price_cents' => 14900, 'billing_cycle' => 'monthly', 'requests_per_month' => 400000, 'requests_per_day' => 20000, 'requests_per_minute' => 300, 'features' => ['Everything in Starter', '3 sites', '3 API keys'], 'limits' => ['sites' => 3], 'sort_order' => 2, 'is_popular' => true, 'is_active' => true],
            ['slug' => 'business', 'name' => 'Business', 'description' => 'For enterprise support teams.', 'price_cents' => 29900, 'billing_cycle' => 'monthly', 'requests_per_month' => 1500000, 'requests_per_day' => 70000, 'requests_per_minute' => 1000, 'features' => ['Everything in Pro', '10 sites', '10 API keys'], 'limits' => ['sites' => 10], 'sort_order' => 3, 'is_popular' => false, 'is_active' => true],
        ]);

        ProductEngagement::query()->updateOrCreate(['product_id' => $shopcoreApi->id], ['downloads' => 0, 'rating' => 4.9, 'version' => '3.1.0']);
        ProductEngagement::query()->updateOrCreate(['product_id' => $helpdeskApi->id], ['downloads' => 0, 'rating' => 4.9, 'version' => '1.2.0']);

        foreach ($commerceFrontends as $index => $data) {
            $this->upsertTemplate($data, [
                'api_slug' => 'shopcore-commerce-api',
                'project' => 'shopcore',
                'category' => 'Ecommerce Frontend Product',
                'featured' => $index < 4,
                'content_label' => 'commerce',
            ]);
        }

        foreach ($helpdeskFrontends as $index => $data) {
            $this->upsertTemplate($data, [
                'api_slug' => 'helpdesk-support-api',
                'project' => 'helpdesk',
                'category' => 'Support Frontend Product',
                'featured' => $index < 4,
                'content_label' => 'support',
            ]);
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $context
     */
    private function upsertTemplate(array $data, array $context): void
    {
        $apiSlug = (string) Arr::get($context, 'api_slug');
        $project = (string) Arr::get($context, 'project');
        $category = (string) Arr::get($context, 'category');
        $featured = (bool) Arr::get($context, 'featured', false);
        $contentLabel = (string) Arr::get($context, 'content_label');
        $segment = (string) Arr::get($data, 'segment', Arr::get($data, 'focus', 'business'));
        $version = (string) Arr::get($data, 'version', '1.0.0');
        $allowed = Arr::get($data, 'allowed', []);

        $product = Product::query()->updateOrCreate(
            ['slug' => (string) $data['slug']],
            [
                'title' => (string) $data['title'],
                'short_description' => sprintf('%s-focused Nuxt full product with store/portal, customer area, and admin dashboard.', ucfirst(str_replace('-', ' ', $segment))),
                'description' => sprintf('Production-ready %s frontend product using a shared hosted API contract.', $contentLabel),
                'content' => $this->buildContent($apiSlug, $allowed, $contentLabel),
                'image' => 'https://images.unsplash.com/photo-1556742393-d75f468bfcb0?w=1200',
                'price' => (float) $data['price'],
                'category' => $category,
                'type' => ProductType::Downloadable,
                'demo_url' => 'https://demo.mintreu.com/'.(string) $data['slug'],
                'documentation_url' => 'https://docs.mintreu.com/'.$project.'/products/'.(string) $data['slug'],
                'version' => $version,
                'downloads' => 0,
                'rating' => 4.7,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => $featured,
                'requires_auth' => true,
                'default_license' => LicenseType::CommercialSingle,
                'meta' => [
                    'project' => $project,
                    'product_kind' => 'nuxt_business_product',
                    'variant_code' => (string) Arr::get($data, 'variant'),
                    'segment_focus' => $segment,
                    'offer_type' => Arr::get($data, 'offer_type'),
                    'api_matches' => [$apiSlug],
                    'requires_api_subscription_slug' => $apiSlug,
                    'allowed_categories' => $allowed,
                    'package_scope' => ['frontend', 'customer_dashboard', 'admin_dashboard'],
                ],
                'api_config' => null,
            ]
        );

        ProductSource::query()->updateOrCreate(
            ['product_id' => $product->id, 'provider' => SourceProvider::LocalStorage],
            [
                'name' => 'Primary Download',
                'description' => 'Nuxt production package (frontend + customer + admin dashboards).',
                'source_url' => 'products/'.(string) $data['slug'].'/v'.$version,
                'version' => $version,
                'file_name' => (string) $data['slug'].'-'.$version.'.zip',
                'metadata' => ['distribution' => 'zip', 'template_type' => 'nuxt_frontend'],
                'is_primary' => true,
                'is_active' => true,
            ]
        );

        ProductEngagement::query()->updateOrCreate(
            ['product_id' => $product->id],
            ['downloads' => 0, 'rating' => 4.7, 'version' => $version]
        );
    }

    /**
     * @param array<int, string> $allowed
     */
    private function buildContent(string $apiSlug, array $allowed, string $label): string
    {
        $allowedHtml = collect($allowed)->map(fn (string $item): string => '<code>'.$item.'</code>')->implode(', ');

        return '<h2>What You Get</h2><ul><li>Frontend experience</li><li>Customer dashboard</li><li>Admin dashboard</li></ul>'
            .'<h2>Category Focus</h2><p>'.$allowedHtml.'</p>'
            .'<h2>API Dependency</h2><p>This '.$label.' product requires <a href="/products/'.$apiSlug.'">'.$apiSlug.'</a>.</p>';
    }

    /**
     * @param  array<int, array<string, mixed>>  $plans
     */
    private function upsertPlans(int $productId, array $plans): void
    {
        $allowedPlanSlugs = array_column($plans, 'slug');

        Plan::query()
            ->where('product_id', $productId)
            ->whereNotIn('slug', $allowedPlanSlugs)
            ->delete();

        foreach ($plans as $plan) {
            Plan::query()->updateOrCreate(
                ['product_id' => $productId, 'slug' => (string) $plan['slug']],
                $plan + ['product_id' => $productId]
            );
        }
    }
}
