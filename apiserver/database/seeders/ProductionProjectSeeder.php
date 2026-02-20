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
                'description' => 'A premium commerce suite built for fast-launch niche online brands.',
                'content' => '<h2>What Clients Get</h2><ul><li>Complete ecommerce operations API access</li><li>Premium niche-ready Nuxt products</li><li>Category-personalized customer journeys</li><li>Fast onboarding for production launch</li></ul><h2>Catalog Examples</h2><ul><li><a href="/products/velori-boutique">Velori Boutique</a></li><li><a href="/products/havena-home">Havena Home</a></li><li><a href="/products/techvora-gadgets">Techvora Gadgets</a></li><li><a href="/products/jewelora-atelier">Jewelora Atelier</a></li></ul>',
                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=1200',
                'category' => 'Ecommerce API + Nuxt Product Suite',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis', 'Docker'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'live_url' => 'https://try-shopcore.mintreu.com',
                'github_url' => null,
            ],
            [
                'title' => 'HelpdeskFlow Support Cloud',
                'description' => 'A modern support suite for ticketing, conversations, live chat, and AI assistance.',
                'content' => '<h2>What Clients Get</h2><ul><li>Support operations API access</li><li>Ready-to-sell support product variants</li><li>Ticket, message, live chat, and AI-focused experiences</li><li>Production-grade workflow and analytics</li></ul><h2>Catalog Examples</h2><ul><li><a href="/products/tixora-support">Tixora Support</a></li><li><a href="/products/copira-ai-desk">Copira AI Desk</a></li><li><a href="/products/cartrio-support-desk">Cartrio Support Desk</a></li><li><a href="/products/relaygo-livechat">Relaygo Livechat</a></li></ul>',
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200',
                'category' => 'Support API + Nuxt Product Suite',
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
