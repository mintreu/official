<?php

namespace Database\Seeders;

use App\Casts\PublishableStatusCast;
use App\Models\Content\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'E-Commerce Platform',
                'description' => 'Full-featured online store with Laravel backend, Nuxt frontend, and native Android app with complete payment integration.',
                'content' => '<h2>Project Overview</h2><p>Built a comprehensive e-commerce solution with multi-platform support including web, mobile, and admin dashboard.</p><h3>Key Features</h3><ul><li>Laravel backend API</li><li>Nuxt.js responsive frontend</li><li>Native Kotlin Android app</li><li>Integrated payment gateways</li><li>Real-time inventory management</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1557821552-17105176677c?w=800',
                'category' => 'Web + Mobile',
                'technologies' => ['Laravel', 'Nuxt.js', 'Kotlin', 'Stripe', 'Razorpay', 'PostgreSQL'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'live_url' => null,
                'github_url' => null,
            ],
            [
                'title' => 'Healthcare Portal',
                'description' => 'HIPAA-compliant patient management system with telemedicine capabilities and desktop clinic app.',
                'content' => '<h2>Healthcare Management Solution</h2><p>Developed a secure healthcare platform with web portal, desktop application, and mobile app for patients.</p>',
                'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800',
                'category' => 'Web + Desktop',
                'technologies' => ['Laravel', 'Livewire', 'Electron', 'WebRTC', 'MongoDB'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'live_url' => null,
                'github_url' => null,
            ],
            [
                'title' => 'FinTech Dashboard',
                'description' => 'Real-time financial analytics dashboard with AI-powered insights and fraud detection.',
                'content' => '<h2>AI-Powered Financial Analytics</h2><p>Created advanced analytics platform with machine learning models for fraud detection and predictive insights.</p>',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800',
                'category' => 'Web',
                'technologies' => ['Next.js', 'Python', 'TensorFlow', 'PostgreSQL', 'Redis'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'live_url' => null,
                'github_url' => null,
            ],
            [
                'title' => 'Delivery App',
                'description' => 'On-demand delivery platform with real-time tracking, routing optimization, and payment processing.',
                'content' => '<h2>On-Demand Delivery Platform</h2><p>Built a complete delivery management system with real-time GPS tracking and route optimization.</p>',
                'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800',
                'category' => 'Mobile + Web',
                'technologies' => ['Kotlin', 'Laravel', 'Google Maps', 'Firebase', 'MySQL'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'live_url' => null,
                'github_url' => null,
            ],
            [
                'title' => 'CRM System',
                'description' => 'Enterprise CRM with FilamentPHP admin panel, automation workflows, and analytics.',
                'content' => '<h2>Enterprise CRM Solution</h2><p>Developed a comprehensive customer relationship management system with powerful automation capabilities.</p>',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800',
                'category' => 'Web',
                'technologies' => ['Laravel', 'FilamentPHP', 'Livewire', 'MySQL', 'Redis'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'live_url' => null,
                'github_url' => null,
            ],
            [
                'title' => 'Learning Platform',
                'description' => 'Interactive e-learning platform with video streaming, assessments, and progress tracking.',
                'content' => '<h2>E-Learning Platform</h2><p>Created an interactive learning management system with video streaming and comprehensive assessment tools.</p>',
                'image' => 'https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=800',
                'category' => 'Web + Mobile',
                'technologies' => ['Nuxt.js', 'Laravel', 'Android', 'AWS S3', 'MySQL'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'live_url' => null,
                'github_url' => null,
            ],
        ];

        foreach ($projects as $projectData) {
            Project::updateOrCreate(
                ['slug' => Str::slug($projectData['title'])],
                $projectData
            );
        }
    }
}
