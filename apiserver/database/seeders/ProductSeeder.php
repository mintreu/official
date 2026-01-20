<?php

namespace Database\Seeders;

use App\Casts\PublishableStatusCast;
use App\Models\Product;
use App\Models\ProductConfig;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // TEMPLATES - FREE
            [
                'title' => 'Vue 3 Dashboard Template',
                'slug' => 'vue3-dashboard-template',
                'description' => 'Free, responsive dashboard template with Vue 3 and Tailwind CSS. Perfect for SaaS applications.',
                'content' => '<h2>Features</h2><ul><li>Vue 3 Composition API</li><li>Tailwind CSS v4</li><li>Responsive Design</li><li>Dark Mode</li><li>Chart Components</li><li>Table Components</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800',
                'price' => 0,
                'category' => 'frontend',
                'type' => 'template',
                'demo_url' => 'https://vue-dashboard-demo.example.com',
                'github_url' => 'https://github.com/example/vue3-dashboard',
                'documentation_url' => 'https://docs.example.com/vue3-dashboard',
                'version' => '1.0.0',
                'downloads' => 5420,
                'rating' => 4.7,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'is_payable' => false,
                'requires_account' => false,
                'default_license_type' => 'FREE_ATTRIBUTION',
            ],

            // TEMPLATES - PAID
            [
                'title' => 'Next.js SaaS Boilerplate',
                'slug' => 'nextjs-saas-boilerplate',
                'description' => 'Complete SaaS starter kit with Next.js 14, Stripe integration, authentication, and database setup.',
                'content' => '<h2>Includes</h2><ul><li>Next.js 14 App Router</li><li>Stripe Payment Integration</li><li>NextAuth v5</li><li>Prisma ORM</li><li>TypeScript</li><li>Tailwind CSS</li><li>Admin Dashboard</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800',
                'price' => 129.99,
                'category' => 'fullstack',
                'type' => 'template',
                'demo_url' => 'https://saas-boilerplate-demo.example.com',
                'github_url' => null,
                'documentation_url' => 'https://docs.example.com/saas-boilerplate',
                'version' => '2.5.1',
                'downloads' => 1240,
                'rating' => 4.9,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'is_payable' => true,
                'requires_account' => true,
                'default_license_type' => 'COMMERCIAL_SINGLE_USE',
            ],

            // BROWSER GAMES - FREE
            [
                'title' => 'Flappy Bird Clone',
                'slug' => 'flappy-bird-clone-game',
                'description' => 'Browser-based Flappy Bird clone built with HTML5 Canvas and Vanilla JS. Play instantly, no installation needed.',
                'content' => '<h2>Game Features</h2><ul><li>Smooth Animations</li><li>Score Tracking</li><li>Difficulty Levels</li><li>Leaderboard Ready</li><li>Mobile Friendly</li><li>Sound Effects</li></ul><h2>Play Online</h2><p>Click the demo link to play instantly in your browser!</p>',
                'image' => 'https://images.unsplash.com/photo-1552820728-8ac41f1ce891?w=800',
                'price' => 0,
                'category' => 'games',
                'type' => 'game',
                'demo_url' => 'https://games.example.com/flappy-bird',
                'github_url' => 'https://github.com/example/flappy-bird-game',
                'documentation_url' => null,
                'version' => '1.2.0',
                'downloads' => 8900,
                'rating' => 4.5,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'is_payable' => false,
                'requires_account' => false,
                'default_license_type' => 'FREE_UNLIMITED',
            ],

            // CODE PACKAGES - FREE
            [
                'title' => 'PHP Validation Library',
                'slug' => 'php-validation-library',
                'description' => 'Lightweight, fast validation library for PHP with support for custom rules and error messages.',
                'content' => '<h2>Features</h2><ul><li>30+ Built-in Rules</li><li>Custom Rule Support</li><li>Error Messages</li><li>Type-Hinted</li><li>Zero Dependencies</li><li>PSR-12 Compliant</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800',
                'price' => 0,
                'category' => 'backend',
                'type' => 'package',
                'demo_url' => null,
                'github_url' => 'https://github.com/example/php-validator',
                'documentation_url' => 'https://docs.example.com/php-validator',
                'version' => '2.0.0',
                'downloads' => 3400,
                'rating' => 4.6,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => false,
                'is_payable' => false,
                'requires_account' => false,
                'default_license_type' => 'FREE_ATTRIBUTION',
            ],

            // CODE PACKAGES - PAID
            [
                'title' => 'Advanced E-Commerce API',
                'slug' => 'advanced-ecommerce-api',
                'description' => 'Production-ready REST API for e-commerce with payment integration, inventory management, and analytics.',
                'content' => '<h2>Endpoints</h2><ul><li>Products Management</li><li>Orders & Payments</li><li>Inventory Tracking</li><li>Customer Management</li><li>Reports & Analytics</li><li>Webhooks</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800',
                'price' => 199.99,
                'category' => 'backend',
                'type' => 'package',
                'demo_url' => 'https://api-demo.example.com/swagger',
                'github_url' => null,
                'documentation_url' => 'https://docs.example.com/ecommerce-api',
                'version' => '3.1.2',
                'downloads' => 420,
                'rating' => 4.8,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'is_payable' => true,
                'requires_account' => true,
                'default_license_type' => 'COMMERCIAL_3_USES',
            ],

            // MEDIA/ASSETS - FREE
            [
                'title' => 'Icon Pack 500+ Icons',
                'slug' => 'icon-pack-500',
                'description' => 'Free SVG icon pack with 500+ carefully crafted icons in multiple styles and sizes.',
                'content' => '<h2>Includes</h2><ul><li>500+ SVG Icons</li><li>Multiple Styles</li><li>All Sizes (16px - 256px)</li><li>Color Variants</li><li>Figma Source File</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800',
                'price' => 0,
                'category' => 'design',
                'type' => 'media',
                'demo_url' => null,
                'github_url' => null,
                'documentation_url' => 'https://docs.example.com/icon-pack',
                'version' => '2.0.0',
                'downloads' => 12500,
                'rating' => 4.9,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'is_payable' => false,
                'requires_account' => false,
                'default_license_type' => 'FREE_ATTRIBUTION',
            ],

            // MEDIA/ASSETS - PAID
            [
                'title' => 'Premium UI Kit - Figma',
                'slug' => 'premium-ui-kit-figma',
                'description' => 'Complete UI kit with 200+ components, styles, and variations. Figma file included.',
                'content' => '<h2>Contains</h2><ul><li>200+ Components</li><li>Color Themes</li><li>Typography Styles</li><li>Responsive Layouts</li><li>Dark Mode</li><li>Figma File</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800',
                'price' => 79.99,
                'category' => 'design',
                'type' => 'media',
                'demo_url' => null,
                'github_url' => null,
                'documentation_url' => 'https://docs.example.com/ui-kit',
                'version' => '1.5.0',
                'downloads' => 2100,
                'rating' => 4.8,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => false,
                'is_payable' => true,
                'requires_account' => true,
                'default_license_type' => 'COMMERCIAL_SINGLE_USE',
            ],

            // WORDPRESS PLUGINS - FREE
            [
                'title' => 'SEO Optimizer Plugin',
                'slug' => 'wordpress-seo-optimizer',
                'description' => 'Free WordPress plugin to optimize SEO with automated meta tags, sitemap generation, and analytics.',
                'content' => '<h2>Features</h2><ul><li>Automated Meta Tags</li><li>XML Sitemap</li><li>Keyword Analysis</li><li>Readability Check</li><li>Schema Markup</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800',
                'price' => 0,
                'category' => 'wordpress',
                'type' => 'plugin',
                'demo_url' => null,
                'github_url' => 'https://github.com/example/wordpress-seo',
                'documentation_url' => 'https://docs.example.com/wordpress-seo',
                'version' => '1.3.0',
                'downloads' => 6700,
                'rating' => 4.7,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'is_payable' => false,
                'requires_account' => false,
                'default_license_type' => 'FREE_ATTRIBUTION',
            ],

            // WORDPRESS PLUGINS - PAID
            [
                'title' => 'Advanced Shop Manager Pro',
                'slug' => 'wordpress-shop-manager-pro',
                'description' => 'Premium WooCommerce plugin with advanced inventory, multi-vendor support, and analytics dashboard.',
                'content' => '<h2>Pro Features</h2><ul><li>Multi-Vendor Support</li><li>Advanced Inventory</li><li>Analytics Dashboard</li><li>Custom Workflows</li><li>API Integration</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800',
                'price' => 149.99,
                'category' => 'wordpress',
                'type' => 'plugin',
                'demo_url' => 'https://demo.example.com/woo-manager',
                'github_url' => null,
                'documentation_url' => 'https://docs.example.com/woo-manager-pro',
                'version' => '2.4.0',
                'downloads' => 890,
                'rating' => 4.9,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'is_payable' => true,
                'requires_account' => true,
                'default_license_type' => 'COMMERCIAL_SINGLE_USE',
            ],

            // GAMES - PAID (Addictive game with premium features)
            [
                'title' => '2048 Game - Premium Version',
                'slug' => '2048-game-premium',
                'description' => 'Browser-based 2048 game with multiplayer, leaderboards, and premium themes.',
                'content' => '<h2>Features</h2><ul><li>Play Online</li><li>Multiplayer Mode</li><li>Global Leaderboard</li><li>Premium Themes</li><li>Statistics</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1552820728-8ac41f1ce891?w=800',
                'price' => 4.99,
                'category' => 'games',
                'type' => 'game',
                'demo_url' => 'https://games.example.com/2048-premium',
                'github_url' => null,
                'documentation_url' => null,
                'version' => '1.0.0',
                'downloads' => 450,
                'rating' => 4.6,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => false,
                'is_payable' => true,
                'requires_account' => true,
                'default_license_type' => 'COMMERCIAL_SINGLE_USE',
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Create default LOCAL_STORAGE config
            ProductConfig::create([
                'product_id' => $product->id,
                'source_type' => 'LOCAL_STORAGE',
                'source_identifier' => "products/{$product->slug}/v{$product->version}",
                'metadata' => ['filename' => "{$product->slug}-{$product->version}.zip"],
                'is_primary' => true,
                'is_private' => false,
            ]);
        }
    }
}
