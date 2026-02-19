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
                'description' => 'Core ecommerce API project powering 20 separate Nuxt business products.',
                'content' => '<h2>Production Scope</h2><ul><li>Hosted API product: <a href="/products/shopcore-commerce-api">shopcore-commerce-api</a></li><li>20 sellable Nuxt frontend products</li><li>Category-focused variants with one shared API contract</li><li>No backend source distribution</li></ul><h2>Catalog Examples</h2><ul><li><a href="/products/velori-boutique">Velori Boutique</a></li><li><a href="/products/havena-home">Havena Home</a></li><li><a href="/products/techvora-gadgets">Techvora Gadgets</a></li><li><a href="/products/jewelora-atelier">Jewelora Atelier</a></li></ul>',
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
                'description' => 'Core support API project powering 10 separate Nuxt support products.',
                'content' => '<h2>Production Scope</h2><ul><li>Hosted API product: <a href="/products/helpdesk-support-api">helpdesk-support-api</a></li><li>10 sellable Nuxt support products</li><li>Ticket, message, live chat, and AI-assisted variants</li><li>No backend source distribution</li></ul><h2>Catalog Examples</h2><ul><li><a href="/products/tixora-support">Tixora Support</a></li><li><a href="/products/copira-ai-desk">Copira AI Desk</a></li><li><a href="/products/cartrio-support-desk">Cartrio Support Desk</a></li><li><a href="/products/relaygo-livechat">Relaygo Livechat</a></li></ul>',
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200',
                'category' => 'Support API + Nuxt Product Suite',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis', 'Docker'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
                'live_url' => 'https://try-helpdesk.mintreu.com',
                'github_url' => null,
            ],
            [
                'title' => 'LicenseOps Activation Cloud',
                'description' => 'Shared licensing and entitlement API project for all Mintreu products.',
                'content' => '<h2>Role in Mother Structure</h2><p>LicenseOps will standardize activation, entitlement checks, and revocation for all product families.</p><h2>Planned Child Products</h2><ul><li>License vendor dashboard frontend</li><li>Client activation frontend</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1554224154-22dec7ec8818?w=1200',
                'category' => 'Platform API Project',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => false,
                'live_url' => null,
                'github_url' => null,
            ],
            [
                'title' => 'NotifyStack Messaging Cloud',
                'description' => 'Planned API project for unified email, SMS, and WhatsApp notification flows.',
                'content' => '<h2>Role in Mother Structure</h2><p>NotifyStack will provide one messaging API contract for multiple frontend communication products.</p><h2>Planned Child Products</h2><ul><li>Campaign operations frontend</li><li>Transactional alert frontend</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1200',
                'category' => 'Platform API Project',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis', 'Docker'],
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => false,
                'live_url' => null,
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
