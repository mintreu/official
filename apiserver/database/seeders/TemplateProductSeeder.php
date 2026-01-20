<?php

namespace Database\Seeders;

use App\Enums\LicenseType;
use App\Enums\ProductType;
use App\Enums\SourceProvider;
use App\Models\Product;
use App\Models\Products\Plan;
use App\Models\Products\ProductSource;
use Illuminate\Database\Seeder;

class TemplateProductSeeder extends Seeder
{
    public function run(): void
    {
        // ===== FREE TEMPLATES =====

        // 1. Free Landing Page Template
        $freeLanding = Product::create([
            'slug' => 'starter-landing-page',
            'title' => 'Starter Landing Page',
            'short_description' => 'Clean, modern landing page template with hero section, features, and CTA.',
            'description' => 'A beautifully designed landing page template perfect for startups, SaaS products, or personal projects. Includes responsive design, dark mode support, and smooth animations.',
            'content' => '<h2>Features</h2><ul><li>Fully responsive design</li><li>Dark mode support</li><li>Smooth scroll animations</li><li>SEO optimized</li><li>Easy to customize</li></ul><h2>Tech Stack</h2><p>Built with HTML5, TailwindCSS, and Alpine.js for interactivity.</p>',
            'image' => null,
            'price' => 0,
            'category' => 'Landing Page',
            'type' => ProductType::Freebie,
            'demo_url' => 'https://demo.mintreu.com/starter-landing',
            'github_url' => 'https://github.com/mintreu/starter-landing-page',
            'documentation_url' => 'https://docs.mintreu.com/starter-landing',
            'version' => '1.0.0',
            'downloads' => 1250,
            'rating' => 4.8,
            'status' => 'Published',
            'featured' => true,
            'requires_auth' => false,
            'default_license' => LicenseType::FreeAttribution,
        ]);

        ProductSource::create([
            'product_id' => $freeLanding->id,
            'provider' => SourceProvider::LocalStorage,
            'name' => 'Full Package',
            'description' => 'Complete template with all assets',
            'source_url' => storage_path('app/templates/starter-landing-page.zip'),
            'version' => '1.0.0',
            'file_name' => 'starter-landing-page.zip',
            'file_size' => 245000,
            'is_primary' => true,
            'is_active' => true,
        ]);

        // 2. Free Portfolio Template
        $freePortfolio = Product::create([
            'slug' => 'developer-portfolio',
            'title' => 'Developer Portfolio',
            'short_description' => 'Minimal portfolio template for developers and designers.',
            'description' => 'Showcase your work with this elegant portfolio template. Features project gallery, about section, skills showcase, and contact form.',
            'content' => '<h2>Perfect For</h2><ul><li>Developers</li><li>Designers</li><li>Freelancers</li><li>Creative professionals</li></ul><h2>Sections</h2><p>Hero, About, Projects, Skills, Testimonials, Contact</p>',
            'image' => null,
            'price' => 0,
            'category' => 'Portfolio',
            'type' => ProductType::Freebie,
            'demo_url' => 'https://demo.mintreu.com/dev-portfolio',
            'github_url' => 'https://github.com/mintreu/developer-portfolio',
            'documentation_url' => 'https://docs.mintreu.com/dev-portfolio',
            'version' => '1.2.0',
            'downloads' => 3420,
            'rating' => 4.9,
            'status' => 'Published',
            'featured' => true,
            'requires_auth' => false,
            'default_license' => LicenseType::FreeAttribution,
        ]);

        ProductSource::create([
            'product_id' => $freePortfolio->id,
            'provider' => SourceProvider::LocalStorage,
            'name' => 'Full Package',
            'description' => 'Complete portfolio template',
            'source_url' => storage_path('app/templates/developer-portfolio.zip'),
            'version' => '1.2.0',
            'file_name' => 'developer-portfolio.zip',
            'file_size' => 380000,
            'is_primary' => true,
            'is_active' => true,
        ]);

        // ===== PAID TEMPLATES =====

        // 3. Premium SaaS Dashboard
        $paidDashboard = Product::create([
            'slug' => 'saas-dashboard-pro',
            'title' => 'SaaS Dashboard Pro',
            'short_description' => 'Complete admin dashboard with charts, tables, and user management.',
            'description' => 'Professional-grade admin dashboard template with 50+ components, multiple layouts, real-time charts, data tables, and authentication pages. Built for serious SaaS applications.',
            'content' => '<h2>What\'s Included</h2><ul><li>50+ UI Components</li><li>10+ Page Templates</li><li>Real-time Charts (Chart.js)</li><li>Advanced Data Tables</li><li>Authentication Pages</li><li>User Management</li><li>Settings Pages</li><li>Invoice Templates</li></ul><h2>Support</h2><p>6 months of premium support and free updates included.</p>',
            'image' => null,
            'price' => 49.00,
            'category' => 'Dashboard',
            'type' => ProductType::Downloadable,
            'demo_url' => 'https://demo.mintreu.com/saas-dashboard-pro',
            'documentation_url' => 'https://docs.mintreu.com/saas-dashboard-pro',
            'version' => '2.1.0',
            'downloads' => 890,
            'rating' => 4.9,
            'status' => 'Published',
            'featured' => true,
            'requires_auth' => true,
            'default_license' => LicenseType::CommercialSingle,
        ]);

        ProductSource::create([
            'product_id' => $paidDashboard->id,
            'provider' => SourceProvider::LocalStorage,
            'name' => 'Full Package',
            'description' => 'Complete dashboard with all components',
            'source_url' => storage_path('app/templates/saas-dashboard-pro.zip'),
            'version' => '2.1.0',
            'file_name' => 'saas-dashboard-pro.zip',
            'file_size' => 2500000,
            'is_primary' => true,
            'is_active' => true,
        ]);

        // Add plans for paid dashboard
        Plan::create([
            'product_id' => $paidDashboard->id,
            'slug' => 'single',
            'name' => 'Single License',
            'description' => 'Use on one project',
            'price_cents' => 4900,
            'billing_cycle' => 'one_time',
            'features' => ['1 Project', '6 Months Support', 'Free Updates', 'Documentation'],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Plan::create([
            'product_id' => $paidDashboard->id,
            'slug' => 'team',
            'name' => 'Team License',
            'description' => 'Use on up to 5 projects',
            'price_cents' => 9900,
            'billing_cycle' => 'one_time',
            'features' => ['5 Projects', '12 Months Support', 'Free Updates', 'Priority Support', 'Source Files'],
            'sort_order' => 2,
            'is_popular' => true,
            'is_active' => true,
        ]);

        Plan::create([
            'product_id' => $paidDashboard->id,
            'slug' => 'unlimited',
            'name' => 'Unlimited License',
            'description' => 'Unlimited projects',
            'price_cents' => 19900,
            'billing_cycle' => 'one_time',
            'features' => ['Unlimited Projects', 'Lifetime Support', 'Free Updates', 'Priority Support', 'Source Files', 'White Label'],
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 4. Premium E-commerce Template
        $paidEcommerce = Product::create([
            'slug' => 'modern-ecommerce',
            'title' => 'Modern E-commerce',
            'short_description' => 'Full-featured e-commerce template with cart, checkout, and product pages.',
            'description' => 'Launch your online store with this comprehensive e-commerce template. Includes product listings, cart functionality, checkout flow, user accounts, and admin dashboard.',
            'content' => '<h2>Pages Included</h2><ul><li>Home with Featured Products</li><li>Product Listing with Filters</li><li>Product Detail Page</li><li>Shopping Cart</li><li>Checkout Flow</li><li>User Dashboard</li><li>Order History</li><li>Wishlist</li></ul><h2>Features</h2><p>Built with modern JavaScript, supports multiple payment gateways integration.</p>',
            'image' => null,
            'price' => 79.00,
            'category' => 'E-commerce',
            'type' => ProductType::Downloadable,
            'demo_url' => 'https://demo.mintreu.com/modern-ecommerce',
            'documentation_url' => 'https://docs.mintreu.com/modern-ecommerce',
            'version' => '1.5.0',
            'downloads' => 562,
            'rating' => 4.7,
            'status' => 'Published',
            'featured' => false,
            'requires_auth' => true,
            'default_license' => LicenseType::CommercialSingle,
        ]);

        ProductSource::create([
            'product_id' => $paidEcommerce->id,
            'provider' => SourceProvider::LocalStorage,
            'name' => 'Full Package',
            'description' => 'Complete e-commerce template',
            'source_url' => storage_path('app/templates/modern-ecommerce.zip'),
            'version' => '1.5.0',
            'file_name' => 'modern-ecommerce.zip',
            'file_size' => 3200000,
            'is_primary' => true,
            'is_active' => true,
        ]);

        Plan::create([
            'product_id' => $paidEcommerce->id,
            'slug' => 'standard',
            'name' => 'Standard',
            'description' => 'Single commercial project',
            'price_cents' => 7900,
            'billing_cycle' => 'one_time',
            'features' => ['1 Project', '6 Months Support', 'Free Updates'],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Plan::create([
            'product_id' => $paidEcommerce->id,
            'slug' => 'extended',
            'name' => 'Extended',
            'description' => 'Multiple projects + resell',
            'price_cents' => 14900,
            'billing_cycle' => 'one_time',
            'features' => ['Unlimited Projects', '12 Months Support', 'Free Updates', 'Resell Rights'],
            'sort_order' => 2,
            'is_popular' => true,
            'is_active' => true,
        ]);

        $this->command->info('Created 4 template products (2 free, 2 paid)');
    }
}
