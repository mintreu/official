<?php

namespace Database\Seeders;

use App\Casts\PublishableStatusCast;
use App\Models\Content\CaseStudy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CaseStudySeeder extends Seeder
{
    public function run(): void
    {
        $caseStudies = [
            [
                'title' => 'E-Commerce Platform with Multi-Platform Support',
                'description' => 'Traditional retailer needed complete digital transformation with web storefront, mobile app, and admin dashboard.',
                'content' => '<h2>Client Challenge</h2><p>A traditional retail business needed to modernize and reach online customers across all platforms while maintaining efficient inventory management.</p><h2>Our Solution</h2><p>We built a scalable platform using Laravel backend, Nuxt.js web frontend, and native Kotlin Android app. Integrated Stripe (global) and Razorpay (India) for payments. FilamentPHP admin panel for inventory management.</p><h2>Results Achieved</h2><ul><li>300% increase in sales within 6 months</li><li>50,000+ daily active users across platforms</li><li>99.9% uptime with zero payment issues</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800',
                'client' => 'RetailCo Global',
                'industry' => 'E-Commerce',
                'duration' => '4 months',
                'technologies' => ['Laravel', 'Nuxt.js', 'Kotlin', 'FilamentPHP', 'Stripe', 'Razorpay', 'Redis', 'PostgreSQL'],
                'challenge' => 'Traditional retailer needed complete digital transformation with web storefront, mobile app, and admin dashboard to compete in the online market.',
                'solution' => 'Built scalable platform using Laravel backend, Nuxt.js web frontend, and native Kotlin Android app. Integrated Stripe (global) and Razorpay (India) for payments. FilamentPHP admin panel for inventory management.',
                'results' => json_encode([
                    ['value' => '300%', 'label' => 'Sales Growth'],
                    ['value' => '50K+', 'label' => 'Daily Users'],
                    ['value' => '99.9%', 'label' => 'Uptime'],
                ]),
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'Healthcare Management System',
                'description' => 'Healthcare provider required HIPAA-compliant system with web portal, desktop app for clinics, and mobile app for patients.',
                'content' => '<h2>Healthcare Challenge</h2><p>A healthcare provider needed a comprehensive system that complies with HIPAA regulations while providing seamless experience across devices.</p><h2>Solution Delivered</h2><p>Developed comprehensive healthcare ecosystem with Laravel backend, Livewire web interface, Electron desktop application, and Kotlin mobile app. End-to-end encryption, telemedicine video integration, and automated appointment reminders.</p><h2>Impact</h2><ul><li>15,000+ active patients managed</li><li>98% patient satisfaction rate</li><li>65% reduction in administrative time</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=800',
                'client' => 'HealthFirst Clinic Network',
                'industry' => 'Healthcare',
                'duration' => '5 months',
                'technologies' => ['Laravel', 'Livewire', 'Electron', 'Kotlin', 'WebRTC', 'MongoDB', 'Node.js'],
                'challenge' => 'Healthcare provider required HIPAA-compliant system with web portal, desktop app for clinics, and mobile app for patients with secure telemedicine capabilities.',
                'solution' => 'Developed comprehensive healthcare ecosystem with Laravel backend, Livewire web interface, Electron desktop application, and Kotlin mobile app. End-to-end encryption, telemedicine video integration, and automated appointment reminders.',
                'results' => json_encode([
                    ['value' => '15K+', 'label' => 'Active Patients'],
                    ['value' => '98%', 'label' => 'Satisfaction'],
                    ['value' => '65%', 'label' => 'Time Saved'],
                ]),
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'AI-Powered FinTech Dashboard',
                'description' => 'Financial startup needed real-time analytics dashboard with ML-based fraud detection and predictive insights.',
                'content' => '<h2>FinTech Challenge</h2><p>A financial technology startup needed advanced analytics with real-time fraud detection to protect their growing customer base.</p><h2>Technical Solution</h2><p>Created advanced analytics platform using Next.js frontend, Python backend for ML models, and Laravel API layer. Integrated TensorFlow for fraud detection, real-time data processing with Redis, and automated reporting.</p><h2>Business Impact</h2><ul><li>$5M+ in fraudulent transactions prevented</li><li>99.7% accuracy in fraud detection</li><li>2,000+ businesses using the platform</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800',
                'client' => 'FinTech Innovations Inc',
                'industry' => 'Financial Technology',
                'duration' => '3 months',
                'technologies' => ['Next.js', 'Python', 'TensorFlow', 'Laravel', 'PostgreSQL', 'Redis', 'Docker'],
                'challenge' => 'Financial startup needed real-time analytics dashboard with ML-based fraud detection and predictive insights to protect customers and provide actionable business intelligence.',
                'solution' => 'Created advanced analytics platform using Next.js frontend, Python backend for ML models, and Laravel API layer. Integrated TensorFlow for fraud detection, real-time data processing with Redis, and automated reporting.',
                'results' => json_encode([
                    ['value' => '$5M+', 'label' => 'Protected'],
                    ['value' => '99.7%', 'label' => 'Accuracy'],
                    ['value' => '2K+', 'label' => 'Businesses'],
                ]),
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
        ];

        foreach ($caseStudies as $caseStudyData) {
            CaseStudy::updateOrCreate(
                ['slug' => Str::slug($caseStudyData['title'])],
                $caseStudyData
            );
        }
    }
}
