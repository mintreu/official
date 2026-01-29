<?php

namespace Database\Seeders;

use App\Casts\PublishableStatusCast;
use App\Enums\LicenseType;
use App\Enums\ProductType;
use App\Enums\SourceProvider;
use App\Models\Product;
use App\Models\Products\ProductSource;
use Illuminate\Database\Seeder;

class FreebieSampleSeeder extends Seeder
{
    public function run(): void
    {
        $freebies = [
            [
                'title' => 'HTML Landing Page Template',
                'slug' => 'html-landing-template-freebie',
                'description' => 'Modern, responsive HTML landing page template. Perfect for marketing pages, portfolios, or product launches. Free to use with attribution.',
                'content' => '<h2>What is Included</h2><ul><li>Fully Responsive Design</li><li>Mobile Optimized</li><li>SEO Friendly HTML</li><li>CSS Gradient Effects</li><li>Call-to-Action Buttons</li><li>Easy to Customize</li></ul><h2>Usage</h2><p>Download, extract, and start using immediately. No build process required!</p>',
                'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800',
                'price' => 0,
                'category' => 'frontend',
                'type' => ProductType::Freebie,
                'demo_url' => 'https://account.mintreu.com/freebies/template-demo',
                'github_url' => null,
                'documentation_url' => null,
                'version' => '1.0.0',
                'downloads' => 0,
                'rating' => 4.5,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'requires_auth' => false,
                'default_license' => LicenseType::FreeAttribution,
            ],

            [
                'title' => 'Snake Game - Browser Edition',
                'slug' => 'snake-game-browser-freebie',
                'description' => 'Classic Snake game playable directly in your browser. No installation needed. Perfect for learning game development basics.',
                'content' => '<h2>Game Features</h2><ul><li>Smooth Gameplay</li><li>Progressive Difficulty</li><li>Score Tracking</li><li>High Score Storage (Local)</li><li>Pause/Resume</li><li>Mobile Friendly</li></ul><h2>How to Play</h2><p>Use arrow keys to move the snake, eat food to grow, and avoid hitting walls or yourself.</p>',
                'image' => 'https://images.unsplash.com/photo-1552820728-8ac41f1ce891?w=800',
                'price' => 0,
                'category' => 'games',
                'type' => ProductType::Freebie,
                'demo_url' => 'https://account.mintreu.com/freebies/snake-game',
                'github_url' => null,
                'documentation_url' => null,
                'version' => '1.0.0',
                'downloads' => 0,
                'rating' => 4.3,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'requires_auth' => false,
                'default_license' => LicenseType::FreeUnlimited,
            ],

            [
                'title' => 'Sample Icon Pack',
                'slug' => 'sample-icon-pack-freebie',
                'description' => 'Curated collection of 50+ SVG icons for web design projects. Perfect for wireframes, prototypes, and production websites.',
                'content' => '<h2>Icon Formats</h2><ul><li>SVG (Scalable)</li><li>PNG (24x24, 48x48, 96x96)</li><li>Source Files (Figma)</li></ul><h2>Usage Rights</h2><p>Free for personal and commercial use with attribution. Perfect for agencies, freelancers, and solo developers.</p>',
                'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800',
                'price' => 0,
                'category' => 'assets',
                'type' => ProductType::Freebie,
                'demo_url' => 'https://account.mintreu.com/freebies/icons',
                'github_url' => null,
                'documentation_url' => null,
                'version' => '1.0.0',
                'downloads' => 0,
                'rating' => 4.6,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'requires_auth' => false,
                'default_license' => LicenseType::FreeAttribution,
            ],

            [
                'title' => 'UI Component Library',
                'slug' => 'ui-components-library-freebie',
                'description' => 'Reusable HTML/CSS UI components: buttons, cards, modals, dropdowns, and more. Great for prototyping and learning.',
                'content' => '<h2>Components Included</h2><ul><li>Buttons (5 variants)</li><li>Cards (3 layouts)</li><li>Modal Dialog</li><li>Dropdown Menu</li><li>Navigation Bar</li><li>Footer Section</li><li>Alert Boxes</li><li>Form Elements</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800',
                'price' => 0,
                'category' => 'frontend',
                'type' => ProductType::Freebie,
                'demo_url' => 'https://account.mintreu.com/freebies/ui-components',
                'github_url' => null,
                'documentation_url' => null,
                'version' => '1.0.0',
                'downloads' => 0,
                'rating' => 4.7,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => false,
                'requires_auth' => false,
                'default_license' => LicenseType::FreeAttribution,
            ],
        ];

        foreach ($freebies as $freebie) {
            $product = Product::create($freebie);

            // Create local storage config for each freebie
            ProductSource::create([
                'product_id' => $product->id,
                'provider' => SourceProvider::LocalStorage,
                'name' => 'Direct Download',
                'source_url' => 'https://example.com/freebies/demo/'.str_replace(' ', '-', strtolower($freebie['title'])).'.zip',
                'metadata' => [
                    'description' => 'Freebie stored in safe location',
                    'type' => 'freebie',
                ],
                'is_primary' => true,
                'is_active' => true,
            ]);
        }
    }
}
