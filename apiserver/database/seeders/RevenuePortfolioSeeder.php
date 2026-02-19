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

class RevenuePortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $storefrontPacks = [
            [
                'slug' => 'velora-boutique-storefront-pack',
                'title' => 'Velora Boutique Storefront Pack',
                'short_description' => 'Premade Nuxt storefront template for dress and boutique businesses.',
                'description' => 'Production-ready boutique storefront for fashion catalogs, promo drops, and curated collections.',
                'content' => '<h2>Best For</h2><ul><li>Dress and boutique sellers</li><li>Seasonal collection launches</li><li>Visual-first catalog browsing</li></ul><h2>Included</h2><ul><li>Category-locked homepage and listing flow</li><li>Product detail and checkout-ready UI</li><li>Shopcore API connector setup guide</li></ul>',
                'price' => 149.00,
                'version' => '1.0.0',
                'niche' => 'boutique',
                'client_variant' => 'client_boutique',
                'allowed_categories' => ['dress', 'boutique', 'fashion'],
            ],
            [
                'slug' => 'playnest-toy-storefront-pack',
                'title' => 'PlayNest Toy Storefront Pack',
                'short_description' => 'Premade Nuxt storefront template for toy and kids item stores.',
                'description' => 'Family-friendly storefront layout optimized for toy discovery, gifting, and age-based navigation.',
                'content' => '<h2>Best For</h2><ul><li>Toy and baby item shops</li><li>Gift and kids catalog campaigns</li><li>Fast category-led navigation</li></ul><h2>Included</h2><ul><li>Category-locked menu and filters</li><li>Gift-ready product card layouts</li><li>Shopcore API connector setup guide</li></ul>',
                'price' => 139.00,
                'version' => '1.0.0',
                'niche' => 'toy',
                'client_variant' => 'client_toy',
                'allowed_categories' => ['toy', 'kids', 'learning-toys'],
            ],
            [
                'slug' => 'havenhaus-furniture-storefront-pack',
                'title' => 'HavenHaus Furniture Storefront Pack',
                'short_description' => 'Premade Nuxt storefront template for furniture and home decor brands.',
                'description' => 'Wide-layout storefront design optimized for furniture browsing, room-wise sections, and premium catalog presentation.',
                'content' => '<h2>Best For</h2><ul><li>Furniture and decor brands</li><li>Room-based catalog presentation</li><li>Higher-ticket product browsing</li></ul><h2>Included</h2><ul><li>Category-locked collection pages</li><li>Rich media-friendly product sections</li><li>Shopcore API connector setup guide</li></ul>',
                'price' => 189.00,
                'version' => '1.0.0',
                'niche' => 'furniture',
                'client_variant' => 'client_furniture',
                'allowed_categories' => ['furniture', 'home-decor', 'living'],
            ],
            [
                'slug' => 'lunamuse-women-storefront-pack',
                'title' => 'LunaMuse Women Storefront Pack',
                'short_description' => 'Premade Nuxt storefront template focused on women fashion and accessories.',
                'description' => 'Conversion-focused women category storefront with curated merchandising blocks and promo-ready sections.',
                'content' => '<h2>Best For</h2><ul><li>Women fashion and accessories stores</li><li>Beauty and lifestyle catalogs</li><li>Campaign and bundle promotions</li></ul><h2>Included</h2><ul><li>Category-locked merchandising flow</li><li>Women-segment specific landing blocks</li><li>Shopcore API connector setup guide</li></ul>',
                'price' => 159.00,
                'version' => '1.0.0',
                'niche' => 'women',
                'client_variant' => 'client_women',
                'allowed_categories' => ['women-fashion', 'women-accessories', 'beauty'],
            ],
        ];

        $supportFrontendPacks = [
            [
                'slug' => 'assistly-support-portal-pack',
                'title' => 'Assistly Support Portal Pack',
                'short_description' => 'Premade Nuxt support portal for ticket-based support operations.',
                'description' => 'Production-ready ticket support template for queues, SLA status, and agent assignment workflows.',
                'content' => '<h2>Offer Type</h2><p>Ticket-based support frontend.</p><h2>Best For</h2><ul><li>Support teams handling structured ticket workflows</li><li>SLA-driven support operations</li></ul><h2>Included</h2><ul><li>Ticket list and detail views</li><li>Status board and assignment UI</li><li>Helpdesk API connector setup guide</li></ul>',
                'price' => 129.00,
                'version' => '1.0.0',
                'variant' => 'support_ticket_portal',
                'offer_type' => 'ticket',
                'allowed_categories' => ['technical-support', 'order-support', 'billing-support'],
            ],
            [
                'slug' => 'caregrid-message-desk-pack',
                'title' => 'CareGrid Message Desk Pack',
                'short_description' => 'Premade Nuxt support frontend for normal message-based support.',
                'description' => 'Inbox-style support frontend where customers and agents communicate through threaded messages.',
                'content' => '<h2>Offer Type</h2><p>Message-based support frontend.</p><h2>Best For</h2><ul><li>Lightweight support teams</li><li>Conversation-first support workflows</li></ul><h2>Included</h2><ul><li>Message inbox and thread UI</li><li>Unread and priority indicators</li><li>Helpdesk API connector setup guide</li></ul>',
                'price' => 99.00,
                'version' => '1.0.0',
                'variant' => 'support_message_portal',
                'offer_type' => 'message',
                'allowed_categories' => ['customer-support', 'account-support', 'general-support'],
            ],
            [
                'slug' => 'livepulse-chat-support-pack',
                'title' => 'LivePulse Chat Support Pack',
                'short_description' => 'Premade Nuxt live-chat support frontend for real-time customer support.',
                'description' => 'Chat-first support frontend with live conversation windows, queue indicators, and escalation actions.',
                'content' => '<h2>Offer Type</h2><p>Live-chat support frontend.</p><h2>Best For</h2><ul><li>Real-time support desks</li><li>Sales and support combined chat workflows</li></ul><h2>Included</h2><ul><li>Live chat room UI</li><li>Agent online and queue states</li><li>Helpdesk API connector setup guide</li></ul>',
                'price' => 169.00,
                'version' => '1.0.0',
                'variant' => 'support_livechat_portal',
                'offer_type' => 'live_chat',
                'allowed_categories' => ['live-support', 'pre-sales-support', 'customer-support'],
            ],
            [
                'slug' => 'aidesk-copilot-support-pack',
                'title' => 'AIDesk Copilot Support Pack',
                'short_description' => 'Premade Nuxt AI-assisted support frontend with agent assist workflows.',
                'description' => 'AI-enabled support frontend with suggested replies, intent hints, and assisted triage experiences.',
                'content' => '<h2>Offer Type</h2><p>AI-assisted support frontend.</p><h2>Best For</h2><ul><li>Teams using AI assist for faster responses</li><li>High-volume support operations</li></ul><h2>Included</h2><ul><li>Suggested reply interface</li><li>Intent and priority hints</li><li>Helpdesk API connector setup guide</li></ul>',
                'price' => 229.00,
                'version' => '1.0.0',
                'variant' => 'support_ai_portal',
                'offer_type' => 'ai_assisted',
                'allowed_categories' => ['ai-support', 'technical-support', 'priority-support'],
            ],
        ];

        $allowedProductSlugs = array_merge(
            ['shopcore-commerce-api', 'helpdesk-support-api'],
            array_column($storefrontPacks, 'slug'),
            array_column($supportFrontendPacks, 'slug')
        );

        Product::query()->whereNotIn('slug', $allowedProductSlugs)->delete();

        $shopcoreApi = Product::updateOrCreate(
            ['slug' => 'shopcore-commerce-api'],
            [
                'title' => 'PulseCart Commerce Cloud API',
                'short_description' => 'Managed Shopcore API subscription for multi-site ecommerce operations.',
                'description' => 'Single hosted commerce API subscription that powers multiple niche storefront templates.',
                'content' => '<h2>What You Get</h2><ul><li>Catalog, cart, checkout, and order APIs</li><li>API keys and rate-limited subscriptions</li><li>Managed infrastructure with no backend ownership</li></ul><h2>How It Works</h2><p>Buy API subscription separately, then connect one or more premade Nuxt storefront products.</p>',
                'image' => 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?w=1200',
                'price' => 0,
                'type' => ProductType::ApiService,
                'demo_url' => 'https://try-shopcore.mintreu.com',
                'documentation_url' => 'https://docs.mintreu.com/shopcore',
                'version' => '3.0.0',
                'downloads' => 0,
                'rating' => 4.9,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'requires_auth' => true,
                'default_license' => LicenseType::ApiSubscription,
                'meta' => [
                    'project' => 'shopcore',
                    'product_kind' => 'api_subscription',
                    'frontend_products' => array_column($storefrontPacks, 'slug'),
                ],
                'api_config' => [
                    'base_url' => 'https://shopcore.mintreu.com/api/v1',
                    'auth' => 'bearer',
                    'supports_webhooks' => true,
                    'supports_idempotency' => true,
                ],
            ]
        );

        $this->upsertPlans($shopcoreApi->id, [
            [
                'slug' => 'launch',
                'name' => 'Launch',
                'description' => 'For single-brand launch stores.',
                'price_cents' => 9900,
                'billing_cycle' => 'monthly',
                'requests_per_month' => 120000,
                'requests_per_day' => 6000,
                'requests_per_minute' => 120,
                'features' => ['Core catalog + order APIs', '1 site', '1 API key', 'Email support'],
                'limits' => ['sites' => 1, 'storefront_variants' => 1],
                'sort_order' => 1,
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'slug' => 'growth',
                'name' => 'Growth',
                'description' => 'For businesses running multiple storefront versions.',
                'price_cents' => 24900,
                'billing_cycle' => 'monthly',
                'requests_per_month' => 500000,
                'requests_per_day' => 25000,
                'requests_per_minute' => 360,
                'features' => ['Everything in Launch', '3 sites', '3 API keys', 'Priority support'],
                'limits' => ['sites' => 3, 'storefront_variants' => 3],
                'sort_order' => 2,
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'scale',
                'name' => 'Scale',
                'description' => 'For agencies and multi-brand operators.',
                'price_cents' => 59900,
                'billing_cycle' => 'monthly',
                'requests_per_month' => 2000000,
                'requests_per_day' => 100000,
                'requests_per_minute' => 1200,
                'features' => ['Everything in Growth', '10 sites', '10 API keys', 'SLA support'],
                'limits' => ['sites' => 10, 'storefront_variants' => 10],
                'sort_order' => 3,
                'is_popular' => false,
                'is_active' => true,
            ],
        ]);

        ProductEngagement::updateOrCreate(
            ['product_id' => $shopcoreApi->id],
            ['downloads' => 0, 'rating' => 4.9, 'version' => '3.0.0']
        );

        $helpdeskApi = Product::updateOrCreate(
            ['slug' => 'helpdesk-support-api'],
            [
                'title' => 'HelpdeskFlow Support API',
                'short_description' => 'Managed helpdesk API subscription for multi-site support operations.',
                'description' => 'Single hosted support API subscription that powers multiple support frontend templates.',
                'content' => '<h2>What You Get</h2><ul><li>Ticket lifecycle, replies, and status APIs</li><li>API keys and rate-limited subscriptions</li><li>Managed infrastructure with no backend ownership</li></ul><h2>How It Works</h2><p>Buy API subscription separately, then connect one or more premade support frontend products.</p>',
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200',
                'price' => 0,
                'type' => ProductType::ApiService,
                'demo_url' => 'https://try-helpdesk.mintreu.com',
                'documentation_url' => 'https://docs.mintreu.com/helpdesk',
                'version' => '1.0.0',
                'downloads' => 0,
                'rating' => 4.9,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'requires_auth' => true,
                'default_license' => LicenseType::ApiSubscription,
                'meta' => [
                    'project' => 'helpdesk',
                    'product_kind' => 'api_subscription',
                    'frontend_products' => array_column($supportFrontendPacks, 'slug'),
                ],
                'api_config' => [
                    'base_url' => 'https://helpdesk-api.mintreu.com/v1',
                    'auth' => 'bearer',
                    'supports_webhooks' => true,
                    'supports_idempotency' => true,
                ],
            ]
        );

        $this->upsertPlans($helpdeskApi->id, [
            [
                'slug' => 'starter',
                'name' => 'Starter',
                'description' => 'For small support teams.',
                'price_cents' => 6900,
                'billing_cycle' => 'monthly',
                'requests_per_month' => 100000,
                'requests_per_day' => 5000,
                'requests_per_minute' => 100,
                'features' => ['Ticket and reply APIs', '1 support site', '1 API key', 'Email support'],
                'limits' => ['sites' => 1, 'agents' => 5],
                'sort_order' => 1,
                'is_popular' => false,
                'is_active' => true,
            ],
            [
                'slug' => 'pro',
                'name' => 'Pro',
                'description' => 'For growing support operations.',
                'price_cents' => 14900,
                'billing_cycle' => 'monthly',
                'requests_per_month' => 400000,
                'requests_per_day' => 20000,
                'requests_per_minute' => 300,
                'features' => ['Everything in Starter', '3 support sites', '3 API keys', 'Priority support'],
                'limits' => ['sites' => 3, 'agents' => 25],
                'sort_order' => 2,
                'is_popular' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'business',
                'name' => 'Business',
                'description' => 'For large support teams and multi-brand operations.',
                'price_cents' => 29900,
                'billing_cycle' => 'monthly',
                'requests_per_month' => 1500000,
                'requests_per_day' => 70000,
                'requests_per_minute' => 1000,
                'features' => ['Everything in Pro', '10 support sites', '10 API keys', 'SLA support'],
                'limits' => ['sites' => 10, 'agents' => 100],
                'sort_order' => 3,
                'is_popular' => false,
                'is_active' => true,
            ],
        ]);

        ProductEngagement::updateOrCreate(
            ['product_id' => $helpdeskApi->id],
            ['downloads' => 0, 'rating' => 4.9, 'version' => '1.0.0']
        );

        foreach ($storefrontPacks as $pack) {
            $product = Product::updateOrCreate(
                ['slug' => $pack['slug']],
                [
                    'title' => $pack['title'],
                    'short_description' => $pack['short_description'],
                    'description' => $pack['description'],
                    'content' => $pack['content'],
                    'image' => 'https://images.unsplash.com/photo-1556742393-d75f468bfcb0?w=1200',
                    'price' => $pack['price'],
                    'type' => ProductType::Downloadable,
                    'demo_url' => 'https://try-shopcore.mintreu.com',
                    'documentation_url' => 'https://docs.mintreu.com/shopcore/storefront-packs',
                    'version' => $pack['version'],
                    'downloads' => 0,
                    'rating' => 4.7,
                    'status' => PublishableStatusCast::PUBLISHED,
                    'featured' => true,
                    'requires_auth' => true,
                    'default_license' => LicenseType::CommercialSingle,
                    'meta' => [
                        'project' => 'shopcore',
                        'product_kind' => 'premade_site_template',
                        'marketing_name' => $pack['title'],
                        'client_variant' => $pack['client_variant'],
                        'category_lock' => true,
                        'allowed_categories' => $pack['allowed_categories'],
                        'requires_api_subscription_slug' => 'shopcore-commerce-api',
                    ],
                    'api_config' => null,
                ]
            );

            ProductSource::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'provider' => SourceProvider::LocalStorage,
                ],
                [
                    'name' => 'Primary Download',
                    'description' => 'Premade Nuxt site template ZIP bundle',
                    'source_url' => 'products/'.$pack['slug'].'/v'.$pack['version'],
                    'version' => $pack['version'],
                    'file_name' => $pack['slug'].'-'.$pack['version'].'.zip',
                    'metadata' => ['distribution' => 'zip', 'template_type' => 'nuxt_frontend'],
                    'is_primary' => true,
                    'is_active' => true,
                ]
            );

            ProductEngagement::updateOrCreate(
                ['product_id' => $product->id],
                ['downloads' => 0, 'rating' => 4.7, 'version' => $pack['version']]
            );
        }

        foreach ($supportFrontendPacks as $pack) {
            $product = Product::updateOrCreate(
                ['slug' => $pack['slug']],
                [
                    'title' => $pack['title'],
                    'short_description' => $pack['short_description'],
                    'description' => $pack['description'],
                    'content' => $pack['content'],
                    'image' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1200',
                    'price' => $pack['price'],
                    'type' => ProductType::Downloadable,
                    'demo_url' => 'https://try-helpdesk.mintreu.com',
                    'documentation_url' => 'https://docs.mintreu.com/helpdesk/templates',
                    'version' => $pack['version'],
                    'downloads' => 0,
                    'rating' => 4.7,
                    'status' => PublishableStatusCast::PUBLISHED,
                    'featured' => true,
                    'requires_auth' => true,
                    'default_license' => LicenseType::CommercialSingle,
                    'meta' => [
                        'project' => 'helpdesk',
                        'product_kind' => 'premade_site_template',
                        'template_variant' => $pack['variant'],
                        'offer_type' => $pack['offer_type'],
                        'allowed_categories' => $pack['allowed_categories'],
                        'requires_api_subscription_slug' => 'helpdesk-support-api',
                    ],
                    'api_config' => null,
                ]
            );

            ProductSource::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'provider' => SourceProvider::LocalStorage,
                ],
                [
                    'name' => 'Primary Download',
                    'description' => 'Premade Nuxt support template ZIP bundle',
                    'source_url' => 'products/'.$pack['slug'].'/v'.$pack['version'],
                    'version' => $pack['version'],
                    'file_name' => $pack['slug'].'-'.$pack['version'].'.zip',
                    'metadata' => ['distribution' => 'zip', 'template_type' => 'nuxt_frontend'],
                    'is_primary' => true,
                    'is_active' => true,
                ]
            );

            ProductEngagement::updateOrCreate(
                ['product_id' => $product->id],
                ['downloads' => 0, 'rating' => 4.7, 'version' => $pack['version']]
            );
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $plans
     */
    private function upsertPlans(int $productId, array $plans): void
    {
        foreach ($plans as $planData) {
            Plan::updateOrCreate(
                ['product_id' => $productId, 'slug' => $planData['slug']],
                $planData + ['product_id' => $productId]
            );
        }
    }
}
