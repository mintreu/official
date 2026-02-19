<?php

namespace Database\Seeders;

use App\Casts\PublishableStatusCast;
use App\Models\Content\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductionProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'PulseCart Commerce Cloud',
                'description' => 'Primary Shopcore API subscription product with separate premade Nuxt storefront products.',
                'content' => '<h2>Production Scope</h2><ul><li>One hosted API product: <code>shopcore-commerce-api</code></li><li>Four premade Nuxt storefront products sold separately</li><li>Category lock enabled per storefront product</li><li>Single backend API contract shared by all storefront variants</li></ul><h2>Current Frontend Products</h2><ul><li>velora-boutique-storefront-pack</li><li>playnest-toy-storefront-pack</li><li>havenhaus-furniture-storefront-pack</li><li>lunamuse-women-storefront-pack</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=1200',
                'category' => 'API SaaS + Nuxt Templates',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis', 'Docker'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'live_url' => 'https://try-shopcore.mintreu.com',
                'github_url' => null,
            ],
            [
                'title' => 'HelpdeskFlow Support Cloud',
                'description' => 'Primary Helpdesk API subscription product with separate premade Nuxt support portal templates.',
                'content' => '<h2>Production Scope</h2><ul><li>One hosted API product: <code>helpdesk-support-api</code></li><li>Multiple premade Nuxt support frontend products sold separately</li><li>Shared ticket API contract across all templates</li><li>Single backend with multi-site frontend deployments</li></ul><h2>Offer Types</h2><ul><li>Ticket support: assistly-support-portal-pack</li><li>Message support: caregrid-message-desk-pack</li><li>Live chat support: livepulse-chat-support-pack</li><li>AI-assisted support: aidesk-copilot-support-pack</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200',
                'category' => 'API SaaS + Nuxt Templates',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis', 'Docker'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'live_url' => 'https://try-helpdesk.mintreu.com',
                'github_url' => null,
            ],
        ];

        $allowedSlugs = collect($projects)
            ->map(fn (array $project): string => Str::slug($project['title']))
            ->all();

        Project::query()->whereNotIn('slug', $allowedSlugs)->delete();

        foreach ($projects as $projectData) {
            Project::updateOrCreate(
                ['slug' => Str::slug($projectData['title'])],
                $projectData
            );
        }
    }
}
