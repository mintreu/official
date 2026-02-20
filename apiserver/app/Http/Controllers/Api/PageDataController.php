<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CaseStudyResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProjectResource;
use App\Models\Content\CaseStudy;
use App\Models\Content\Project;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class PageDataController extends Controller
{
    /**
     * Provide the data for the homepage.
     *
     * @return JsonResponse
     */
    public function home(): JsonResponse
    {
        $featuredProjects = Project::query()
            ->where('status', 'Published')
            ->where('featured', true)
            ->latest()
            ->limit(6)
            ->get();

        $featuredCaseStudies = CaseStudy::query()
            ->where('status', 'Published')
            ->where('featured', true)
            ->latest()
            ->limit(3)
            ->get();

        $featuredProducts = Product::query()
            ->where('status', 'Published')
            ->where('featured', true)
            ->latest()
            ->limit(6)
            ->with(['categories', 'engagement'])
            ->get();

        $technologies = collect()
            ->merge(
                Project::query()
                    ->where('status', 'Published')
                    ->pluck('technologies')
                    ->flatten(1)
            )
            ->merge(
                CaseStudy::query()
                    ->where('status', 'Published')
                    ->pluck('technologies')
                    ->flatten(1)
            )
            ->filter()
            ->unique()
            ->values();

        $data = [
            'featured_projects' => ProjectResource::collection($featuredProjects)->resolve(),
            'case_studies' => CaseStudyResource::collection($featuredCaseStudies)->resolve(),
            'products' => ProductResource::collection($featuredProducts)->resolve(),
            'stats' => [
                ['value' => Project::where('status', 'Published')->count(), 'suffix' => '+', 'label' => 'Projects Delivered'],
                ['value' => Product::where('status', 'Published')->where('type', 'downloadable')->count(), 'suffix' => '+', 'label' => 'Ready Products'],
                ['value' => Product::where('status', 'Published')->whereIn('type', ['api_service', 'api_referral'])->count(), 'suffix' => '', 'label' => 'API Projects'],
                ['value' => $technologies->count(), 'suffix' => '+', 'label' => 'Technologies'],
            ],
            'services' => [
                [
                    'title' => 'Commerce API Platform',
                    'icon' => 'lucide:shopping-bag',
                    'description' => 'PulseCart Commerce Cloud API with catalog, orders, payments, vendor and licensing flows.',
                    'features' => ['Catalog and order APIs', 'Payment gateway orchestration', 'Vendor provisioning', 'License validation'],
                ],
                [
                    'title' => 'Support API Platform',
                    'icon' => 'lucide:messages-square',
                    'description' => 'HelpdeskFlow Support API powering ticket, messaging, live chat and AI-assisted flows.',
                    'features' => ['Ticket pipeline APIs', 'Realtime message channels', 'Agent analytics', 'Knowledge integrations'],
                ],
                [
                    'title' => 'Nuxt Commerce Products',
                    'icon' => 'lucide:store',
                    'description' => 'Category-personalized Nuxt full products for ecommerce verticals on shared API backbone.',
                    'features' => ['Store + customer dashboard', 'Vendor/admin control panel', 'Brand-personalized UI', 'Fast launch stack'],
                ],
                [
                    'title' => 'Nuxt Support Products',
                    'icon' => 'lucide:headset',
                    'description' => 'Support frontend products for ticket desk, message desk, live chat and AI copilot workflows.',
                    'features' => ['Customer support portal', 'Agent workflow panels', 'Realtime conversations', 'Category-specific UX'],
                ],
                [
                    'title' => 'SaaS License Orchestration',
                    'icon' => 'lucide:key-round',
                    'description' => 'Centralized license, site binding, subscription, renewal and API key lifecycle orchestration.',
                    'features' => ['Plan-aware limits', 'Site-wise licenses', 'Auto renewal flows', 'Credential management'],
                ],
                [
                    'title' => 'Deploy and Operations',
                    'icon' => 'lucide:server-cog',
                    'description' => 'Production deployments across local, staging, demo and production environments with observability.',
                    'features' => ['Domain/subdomain mapping', 'Release channels', 'Operational monitoring', 'Backup and rollback'],
                ],
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
            'technologies' => $technologies->all(),
            'socials' => [
                ['name' => 'GitHub', 'url' => 'https://github.com', 'icon' => 'lucide:github'],
                ['name' => 'Twitter', 'url' => 'https://twitter.com', 'icon' => 'lucide:twitter'],
                ['name' => 'LinkedIn', 'url' => 'https://linkedin.com', 'icon' => 'lucide:linkedin'],
                ['name' => 'Dev.to', 'url' => 'https://dev.to', 'icon' => 'lucide:pen-tool']
            ],
            'paymentMethods' => [
                ['name' => 'Stripe', 'icon' => 'lucide:credit-card', 'color' => 'text-blueprint-600'],
                ['name' => 'Cashfree', 'icon' => 'lucide:banknote', 'color' => 'text-green-600'],
                ['name' => 'Razorpay', 'icon' => 'lucide:indian-rupee', 'color' => 'text-mintreu-red-600'],
                ['name' => 'Native', 'icon' => 'lucide:wallet', 'color' => 'text-titanium-500'],
            ],
            'quoteSteps' => [
                ['title' => 'Send Requirements', 'description' => 'Share scope, product type and launch target.'],
                ['title' => 'Receive Proposal', 'description' => 'Get plan, timeline, pricing and delivery milestones.'],
                ['title' => 'Go Live', 'description' => 'Deploy product with support, billing and license handover.'],
            ],
        ];

        return response()->json($data);
    }
}
