<?php

namespace Database\Seeders;

use App\Casts\PublishableStatusCast;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'title' => 'Laravel Admin Panel',
                'description' => 'Complete admin dashboard with FilamentPHP, 50+ components, dark mode, and authentication. Ready to use with RBAC system.',
                'content' => '<h2>Features</h2><ul><li>FilamentPHP v3 Integration</li><li>50+ Pre-built Components</li><li>Dark Mode Support</li><li>Role-Based Access Control</li><li>User Management</li><li>Activity Logs</li></ul><h2>Requirements</h2><ul><li>PHP 8.2+</li><li>Laravel 11+</li><li>MySQL/PostgreSQL</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800',
                'price' => 79,
                'category' => 'plugin',
                'type' => 'premium',
                'download_url' => null,
                'demo_url' => 'https://demo.example.com/admin',
                'github_url' => null,
                'documentation_url' => null,
                'version' => '1.0.0',
                'downloads' => 1250,
                'rating' => 4.8,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'Nuxt Starter Kit',
                'description' => 'Production-ready Nuxt.js boilerplate with authentication, API integration, and SEO optimization. Built with TypeScript.',
                'content' => '<h2>What\'s Included</h2><ul><li>Nuxt 3 Latest Version</li><li>TypeScript Configuration</li><li>Tailwind CSS v4</li><li>Authentication Ready</li><li>API Integration</li><li>SEO Optimized</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800',
                'price' => 49,
                'category' => 'theme',
                'type' => 'premium',
                'download_url' => null,
                'demo_url' => 'https://demo.example.com/nuxt',
                'github_url' => null,
                'documentation_url' => null,
                'version' => '2.1.0',
                'downloads' => 2100,
                'rating' => 4.9,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'Android UI Kit',
                'description' => 'Beautiful Material Design components for Android apps with Kotlin code samples. 30+ screens included.',
                'content' => '<h2>Components</h2><ul><li>Material Design 3</li><li>30+ Pre-designed Screens</li><li>Kotlin Source Code</li><li>Dark Theme Support</li><li>Jetpack Compose</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=800',
                'price' => 59,
                'category' => 'theme',
                'type' => 'premium',
                'download_url' => null,
                'demo_url' => null,
                'github_url' => null,
                'documentation_url' => null,
                'version' => '1.5.2',
                'downloads' => 890,
                'rating' => 4.7,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'REST API Starter',
                'description' => 'Laravel REST API boilerplate with authentication, rate limiting, and Swagger documentation. Production-ready.',
                'content' => '<h2>API Features</h2><ul><li>JWT Authentication</li><li>Rate Limiting</li><li>Swagger Documentation</li><li>CORS Configuration</li><li>Error Handling</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800',
                'price' => 39,
                'category' => 'api',
                'type' => 'premium',
                'download_url' => null,
                'demo_url' => 'https://api-demo.example.com/docs',
                'github_url' => null,
                'documentation_url' => null,
                'version' => '3.0.1',
                'downloads' => 3400,
                'rating' => 4.9,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'Payment Gateway Pack',
                'description' => 'Ready-to-use payment integration for Stripe, PayPal, Razorpay, and Cashfree with webhook handlers and invoice system.',
                'content' => '<h2>Supported Gateways</h2><ul><li>Stripe Integration</li><li>PayPal Integration</li><li>Razorpay (India)</li><li>Cashfree (India)</li><li>Webhook Handlers</li><li>Invoice Generation</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=800',
                'price' => 69,
                'category' => 'plugin',
                'type' => 'premium',
                'download_url' => null,
                'demo_url' => null,
                'github_url' => null,
                'documentation_url' => null,
                'version' => '2.3.0',
                'downloads' => 1680,
                'rating' => 4.8,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'Code Snippets Library',
                'description' => 'Collection of 100+ code snippets for Laravel, Nuxt, React, and Kotlin development. Well documented with examples.',
                'content' => '<h2>Snippet Categories</h2><ul><li>Laravel Helpers</li><li>Vue/Nuxt Components</li><li>React Hooks</li><li>Kotlin Extensions</li><li>Database Queries</li><li>API Integrations</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1542831371-29b0f74f9713?w=800',
                'price' => 0,
                'category' => 'freebie',
                'type' => 'free',
                'download_url' => 'https://github.com/example/snippets',
                'demo_url' => null,
                'github_url' => 'https://github.com/example/snippets',
                'documentation_url' => null,
                'version' => '1.8.0',
                'downloads' => 8500,
                'rating' => 4.9,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['slug' => Str::slug($productData['title'])],
                $productData
            );
        }
    }
}
