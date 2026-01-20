<?php

namespace Database\Seeders;

use App\Casts\PublishableStatusCast;
use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'title' => 'Building Scalable Laravel APIs',
                'excerpt' => 'Learn best practices for building production-ready Laravel REST APIs with proper architecture and security.',
                'content' => '<h2>Introduction</h2><p>Building scalable APIs requires proper planning and architecture. In this guide, we\'ll explore best practices for Laravel API development.</p><h2>Key Topics</h2><ul><li>RESTful API Design</li><li>Authentication with Sanctum</li><li>Rate Limiting</li><li>Error Handling</li><li>API Versioning</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800',
                'category' => 'Laravel',
                'tags' => ['Laravel', 'API', 'Backend', 'REST'],
                'author' => 'Mintreu Team',
                'reading_time' => 8,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Nuxt 3 Performance Optimization',
                'excerpt' => 'Discover techniques to optimize your Nuxt 3 application for maximum performance and SEO.',
                'content' => '<h2>Performance Matters</h2><p>Optimizing Nuxt 3 applications is crucial for user experience and SEO rankings.</p><h2>Optimization Techniques</h2><ul><li>Code Splitting</li><li>Image Optimization</li><li>Lazy Loading</li><li>Caching Strategies</li><li>SSR vs SSG</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800',
                'category' => 'Nuxt',
                'tags' => ['Nuxt', 'Vue', 'Performance', 'SEO'],
                'author' => 'Mintreu Team',
                'reading_time' => 10,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Modern Android Development with Kotlin',
                'excerpt' => 'Comprehensive guide to building modern Android apps using Kotlin and Jetpack Compose.',
                'content' => '<h2>Kotlin for Android</h2><p>Kotlin has become the preferred language for Android development. Learn modern approaches.</p><h2>Topics Covered</h2><ul><li>Jetpack Compose</li><li>Material Design 3</li><li>Coroutines</li><li>Architecture Components</li><li>Testing</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1607252650355-f7fd0460ccdb?w=800',
                'category' => 'Mobile',
                'tags' => ['Android', 'Kotlin', 'Mobile', 'Jetpack Compose'],
                'author' => 'Mintreu Team',
                'reading_time' => 12,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Database Design Best Practices',
                'excerpt' => 'Essential database design principles for building scalable and maintainable applications.',
                'content' => '<h2>Database Fundamentals</h2><p>Good database design is the foundation of any successful application.</p><h2>Best Practices</h2><ul><li>Normalization</li><li>Indexing Strategies</li><li>Query Optimization</li><li>Data Integrity</li><li>Backup Strategies</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=800',
                'category' => 'Database',
                'tags' => ['Database', 'MySQL', 'PostgreSQL', 'Architecture'],
                'author' => 'Mintreu Team',
                'reading_time' => 15,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => false,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'FilamentPHP: Rapid Admin Panel Development',
                'excerpt' => 'Build beautiful admin panels in minutes with FilamentPHP. A complete guide for Laravel developers.',
                'content' => '<h2>Why FilamentPHP?</h2><p>FilamentPHP makes building admin panels incredibly fast and enjoyable.</p><h2>Key Features</h2><ul><li>Beautiful UI</li><li>Form Builder</li><li>Table Builder</li><li>Dark Mode</li><li>Plugins Ecosystem</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800',
                'category' => 'Laravel',
                'tags' => ['FilamentPHP', 'Laravel', 'Admin Panel', 'UI'],
                'author' => 'Mintreu Team',
                'reading_time' => 7,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => false,
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'TypeScript Best Practices for Vue/Nuxt',
                'excerpt' => 'Maximize type safety and developer experience in your Vue and Nuxt applications with TypeScript.',
                'content' => '<h2>TypeScript in Vue Ecosystem</h2><p>TypeScript enhances the development experience with strong typing and better IDE support.</p><h2>Best Practices</h2><ul><li>Type Definitions</li><li>Composables Typing</li><li>Props Validation</li><li>API Types</li><li>Generic Components</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1516116216624-53e697fedbea?w=800',
                'category' => 'Nuxt',
                'tags' => ['TypeScript', 'Vue', 'Nuxt', 'Best Practices'],
                'author' => 'Mintreu Team',
                'reading_time' => 9,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => false,
                'published_at' => now()->subDays(15),
            ],
        ];

        foreach ($articles as $articleData) {
            Article::updateOrCreate(
                ['slug' => Str::slug($articleData['title'])],
                $articleData
            );
        }
    }
}
