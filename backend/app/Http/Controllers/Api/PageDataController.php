<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;

class PageDataController extends Controller
{
    /**
     * Provide the data for the homepage.
     *
     * @return JsonResponse
     */
    public function home(): JsonResponse
    {
        $basePath = base_path('../docs/backend/jsons/');

        $data = [
            'services' => json_decode(File::get($basePath . 'services.json'), true),
            'projects' => json_decode(File::get($basePath . 'projects.json'), true),
            'caseStudies' => json_decode(File::get($basePath . 'case_studies.json'), true),
            'marketplace' => json_decode(File::get($basePath . 'marketplace_products.json'), true),
            // Add other static data if needed by the frontend
            'stats' => [
                ['value' => '100+', 'label' => 'Projects Delivered'],
                ['value' => '50+', 'label' => 'Happy Clients'],
                ['value' => '5+', 'label' => 'Years Experience'],
                ['value' => '20+', 'label' => 'Technologies']
            ],
            'comparisonData' => [
                ['feature' => 'Availability', 'mintreu' => '24/7', 'agency' => 'Business Hours', 'platform' => 'Variable'],
                ['feature' => 'Response Time', 'mintreu' => '< 2 hours', 'agency' => '1-2 days', 'platform' => 'Variable'],
                ['feature' => 'Communication', 'mintreu' => 'Direct', 'agency' => 'Via Manager', 'platform' => 'Platform Only'],
                ['feature' => 'Platforms', 'mintreu' => 'Web+Mobile+Desktop', 'agency' => 'Usually Web Only', 'platform' => 'Varies'],
                ['feature' => 'Flexibility', 'mintreu' => 'Very High', 'agency' => 'Limited', 'platform' => 'Medium'],
                ['feature' => 'Cost', 'mintreu' => 'Competitive', 'agency' => 'High', 'platform' => 'Variable'],
                ['feature' => 'Quality', 'mintreu' => 'Excellent', 'agency' => 'Good', 'platform' => 'Varies'],
                ['feature' => 'Speed', 'mintreu' => 'Fast', 'agency' => 'Slow', 'platform' => 'Varies']
            ],
            'soloFeatures' => [
                'Direct communication with expert developer',
                'Personalized attention to project needs',
                'Flexible working hours and timeline',
                'Cost-effective development solutions',
                'Full code ownership and documentation',
                'Ongoing support and maintenance'
            ],
            'teamFeatures' => [
                'Team of specialized experts (Laravel, Nuxt, Kotlin)',
                'Parallel development for faster delivery',
                'Enterprise-grade code quality',
                'Dedicated project manager',
                'Priority 24/7 support',
                'Cross-platform expertise (Web+Mobile+Desktop)'
            ],
            'technologies' => [
                'Laravel', 'Livewire', 'FilamentPHP', 'Nuxt', 'Next.js', 'Vue', 'React',
                'TypeScript', 'Node.js', 'Python', 'Kotlin', 'Android', 'C#', 'C++',
                'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'TensorFlow', 'Docker'
            ],
            'socials' => [
                ['name' => 'GitHub', 'url' => 'https://github.com', 'icon' => 'lucide:github'],
                ['name' => 'Twitter', 'url' => 'https://twitter.com', 'icon' => 'lucide:twitter'],
                ['name' => 'LinkedIn', 'url' => 'https://linkedin.com', 'icon' => 'lucide:linkedin'],
                ['name' => 'Dev.to', 'url' => 'https://dev.to', 'icon' => 'lucide:pen-tool']
            ]
        ];

        return response()->json($data);
    }
}
