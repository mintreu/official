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
                'title' => 'Shopcore Boutique Template Rollout (Internal Pilot)',
                'description' => 'Internal production pilot using one API subscription and one niche Nuxt storefront product.',
                'content' => '<h2>Context</h2><p>Validated the separated product model: API subscription and frontend template sold independently.</p><h2>Configured Products</h2><ul><li>API: <code>shopcore-commerce-api</code></li><li>Frontend: <code>velora-boutique-storefront-pack</code></li></ul><h2>Configuration</h2><ul><li>Variant code: <code>client_boutique</code></li><li>Category lock: dress, boutique, fashion</li><li>Frontend ZIP mapped to Shopcore API base URL</li></ul>',
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=1200',
                'client' => 'Mintreu Internal Pilot',
                'industry' => 'Ecommerce SaaS',
                'duration' => '2 days',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis'],
                'challenge' => 'Needed production-proof validation that frontend templates can be sold as products while backend API remains shared.',
                'solution' => 'Used Shopcore API subscription plus one category-locked storefront template with explicit variant metadata.',
                'results' => json_encode([
                    ['value' => '1', 'label' => 'API Product Used'],
                    ['value' => '1', 'label' => 'Template Product Used'],
                    ['value' => '3', 'label' => 'Locked Categories'],
                ]),
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'Shopcore Multi-Template Catalog Mapping (Production Prep)',
                'description' => 'Prepared production-ready mapping of one API product to multiple niche Nuxt storefront products.',
                'content' => '<h2>Context</h2><p>Prepared the product catalog so Shopcore API page can list available Nuxt storefront products.</p><h2>Mapped Frontend Products</h2><ul><li>velora-boutique-storefront-pack</li><li>playnest-toy-storefront-pack</li><li>havenhaus-furniture-storefront-pack</li><li>lunamuse-women-storefront-pack</li></ul><h2>Shared Rule</h2><p>All template products point to one API subscription product: <code>shopcore-commerce-api</code>.</p>',
                'image' => 'https://images.unsplash.com/photo-1556742393-d75f468bfcb0?w=1200',
                'client' => 'Mintreu Production Catalog',
                'industry' => 'Digital Product Platform',
                'duration' => '1 day',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis'],
                'challenge' => 'Needed clear production catalog separation between API subscriptions and premade frontend products.',
                'solution' => 'Implemented strict slug-based mapping and metadata linking frontend products to the single Shopcore API product.',
                'results' => json_encode([
                    ['value' => '1', 'label' => 'Shared API Product'],
                    ['value' => '4', 'label' => 'Frontend Products'],
                    ['value' => '4', 'label' => 'Variant Codes'],
                ]),
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => true,
            ],
            [
                'title' => 'Helpdesk Multi-Site Template Mapping (Production Prep)',
                'description' => 'Prepared production-ready mapping of one helpdesk API product to multiple Nuxt support frontend products.',
                'content' => '<h2>Context</h2><p>Confirmed support product catalog can follow the same model as Shopcore: one API subscription, multiple frontend products.</p><h2>Mapped Frontend Products</h2><ul><li>assistly-support-portal-pack (ticket)</li><li>caregrid-message-desk-pack (message)</li><li>livepulse-chat-support-pack (live chat)</li><li>aidesk-copilot-support-pack (AI-assisted)</li></ul><h2>Shared Rule</h2><p>All support templates use one API subscription product: <code>helpdesk-support-api</code>.</p>',
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1200',
                'client' => 'Mintreu Production Catalog',
                'industry' => 'Support SaaS',
                'duration' => '1 day',
                'technologies' => ['Laravel', 'Nuxt', 'MySQL', 'Redis'],
                'challenge' => 'Needed reusable support frontend templates while keeping ticket backend unified and managed.',
                'solution' => 'Linked each template product with metadata to one hosted helpdesk API product and shared contract.',
                'results' => json_encode([
                    ['value' => '1', 'label' => 'Shared API Product'],
                    ['value' => '4', 'label' => 'Frontend Products'],
                    ['value' => '4', 'label' => 'Offer Types'],
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
