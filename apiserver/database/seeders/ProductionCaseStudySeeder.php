<?php

namespace Database\Seeders;

use App\Casts\PublishableStatusCast;
use App\Models\Content\CaseStudy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductionCaseStudySeeder extends Seeder
{
    public function run(): void
    {
        $caseStudies = [
            [
                'title' => 'Shopcore Fashion Launch Pack Rollout',
                'description' => 'Production launch validation for fashion-focused commerce product stack.',
                'content' => '<h2>Products Used</h2><ul><li><a href="/products/shopcore-commerce-api">shopcore-commerce-api</a></li><li><a href="/products/velori-boutique">velori-boutique</a></li><li><a href="/products/lunera-divas">lunera-divas</a></li></ul><h2>Execution</h2><p>Confirmed one API subscription can power multiple niche fashion frontends while keeping a shared backend contract.</p>',
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=1200',
                'client' => 'Mintreu Launch Program',
                'industry' => 'Ecommerce SaaS',
                'duration' => '3 days',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis'],
                'challenge' => 'Needed a clear separation between API subscription revenue and standalone frontend product revenue.',
                'solution' => 'Launched with one API product and two verticalized frontend products mapped by metadata.',
                'results' => json_encode([
                    ['value' => '1', 'label' => 'API Product Used'],
                    ['value' => '2', 'label' => 'Frontend Products Used'],
                    ['value' => '100%', 'label' => 'Shared API Contract'],
                ]),
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'Shopcore 20-Product Commerce Catalog Build',
                'description' => 'Expanded one ecommerce API into 20 sellable niche frontend products.',
                'content' => '<h2>Catalog Rule</h2><p>All commerce products include <code>meta.api_matches = [shopcore-commerce-api]</code>.</p><h2>Examples</h2><ul><li><a href="/products/havena-home">havena-home</a></li><li><a href="/products/techvora-gadgets">techvora-gadgets</a></li><li><a href="/products/jewelora-atelier">jewelora-atelier</a></li></ul>',
                'image' => 'https://images.unsplash.com/photo-1556742393-d75f468bfcb0?w=1200',
                'client' => 'Mintreu Production Catalog',
                'industry' => 'Digital Product Platform',
                'duration' => '2 days',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis'],
                'challenge' => 'Needed scale without backend duplication.',
                'solution' => 'Used one shared API and structured metadata to create 20 distinct product identities.',
                'results' => json_encode([
                    ['value' => '1', 'label' => 'Shared API Product'],
                    ['value' => '20', 'label' => 'Frontend Products'],
                    ['value' => '20', 'label' => 'Unique Variant Codes'],
                ]),
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'Helpdesk 10-Product Support Catalog Build',
                'description' => 'Expanded one support API into 10 role-specific frontend products.',
                'content' => '<h2>Products Used</h2><ul><li><a href="/products/helpdesk-support-api">helpdesk-support-api</a></li><li><a href="/products/tixora-support">tixora-support</a></li><li><a href="/products/copira-ai-desk">copira-ai-desk</a></li><li><a href="/products/relaygo-livechat">relaygo-livechat</a></li></ul><h2>Catalog Rule</h2><p>All support frontends map to one managed API subscription.</p>',
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200',
                'client' => 'Mintreu Production Catalog',
                'industry' => 'Support SaaS',
                'duration' => '2 days',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis'],
                'challenge' => 'Needed product variety without fragmenting support backend logic.',
                'solution' => 'Built vertical support UI products while reusing one API contract.',
                'results' => json_encode([
                    ['value' => '1', 'label' => 'Shared API Product'],
                    ['value' => '10', 'label' => 'Frontend Products'],
                    ['value' => '4', 'label' => 'Offer Types'],
                ]),
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'Commerce + Support Cross-Sell Launch Structure',
                'description' => 'Linked commerce and support product families for stronger deal size.',
                'content' => '<h2>Cross-Sell Stack</h2><ul><li><a href="/products/shopcore-commerce-api">shopcore-commerce-api</a> + <a href="/products/velori-boutique">velori-boutique</a></li><li><a href="/products/helpdesk-support-api">helpdesk-support-api</a> + <a href="/products/cartrio-support-desk">cartrio-support-desk</a></li></ul><p>Used bundled positioning to increase average order value and recurring conversion.</p>',
                'image' => 'https://images.unsplash.com/photo-1556742031-c6961e8560b0?w=1200',
                'client' => 'Mintreu Revenue Program',
                'industry' => 'Commerce + Support SaaS',
                'duration' => '1 week',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL'],
                'challenge' => 'Needed better conversion path from one-time frontend sales to recurring API plans.',
                'solution' => 'Introduced product-family bundles with clear API dependency messaging.',
                'results' => json_encode([
                    ['value' => '2', 'label' => 'API Families Linked'],
                    ['value' => '30', 'label' => 'Ready Sellable Frontends'],
                    ['value' => '1', 'label' => 'Unified Catalog Narrative'],
                ]),
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
        ];

        $allowedSlugs = collect($caseStudies)
            ->map(fn (array $caseStudy): string => Str::slug($caseStudy['title']))
            ->all();

        CaseStudy::query()->whereNotIn('slug', $allowedSlugs)->delete();

        foreach ($caseStudies as $caseStudyData) {
            CaseStudy::updateOrCreate(
                ['slug' => Str::slug($caseStudyData['title'])],
                $caseStudyData
            );
        }
    }
}
