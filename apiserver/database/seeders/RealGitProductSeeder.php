<?php

namespace Database\Seeders;

use App\Casts\PublishableStatusCast;
use App\Enums\LicenseType;
use App\Enums\ProductType;
use App\Enums\SourceProvider;
use App\Models\Product;
use App\Models\Products\ProductSource;
use App\Services\GitReleaseService;
use Illuminate\Database\Seeder;

/**
 * Seeds real products from public GitHub repositories
 *
 * Tests the GitReleaseService and download flow with actual repositories
 */
class RealGitProductSeeder extends Seeder
{
    public function __construct(
        private GitReleaseService $gitService
    ) {}

    public function run(): void
    {
        $this->command->info('Seeding real Git repository products...');

        // Popular open-source templates and tools
        $repos = [
            // Free HTML/CSS Templates
            [
                'owner' => 'tailwindlabs',
                'repo' => 'tailwindcss',
                'title' => 'Tailwind CSS',
                'category' => 'frontend',
                'type' => ProductType::Freebie,
                'license' => LicenseType::FreeUnlimited,
                'description' => 'A utility-first CSS framework for rapid UI development. Build modern websites without ever leaving your HTML.',
                'demo_url' => 'https://tailwindcss.com',
            ],
            [
                'owner' => 'vitejs',
                'repo' => 'vite',
                'title' => 'Vite',
                'category' => 'frontend',
                'type' => ProductType::Freebie,
                'license' => LicenseType::FreeUnlimited,
                'description' => 'Next Generation Frontend Tooling. Get ready for a development environment that can finally catch up with you.',
                'demo_url' => 'https://vitejs.dev',
            ],
            [
                'owner' => 'nuxt',
                'repo' => 'nuxt',
                'title' => 'Nuxt Framework',
                'category' => 'fullstack',
                'type' => ProductType::Freebie,
                'license' => LicenseType::FreeUnlimited,
                'description' => 'The Intuitive Vue Framework. Build your next Vue.js application with confidence using Nuxt.',
                'demo_url' => 'https://nuxt.com',
            ],
            [
                'owner' => 'laravel',
                'repo' => 'laravel',
                'title' => 'Laravel Framework',
                'category' => 'backend',
                'type' => ProductType::Freebie,
                'license' => LicenseType::FreeUnlimited,
                'description' => 'The PHP Framework for Web Artisans. Laravel is a web application framework with expressive, elegant syntax.',
                'demo_url' => 'https://laravel.com',
            ],
            // Downloadable with releases
            [
                'owner' => 'filamentphp',
                'repo' => 'filament',
                'title' => 'Filament Admin Panel',
                'category' => 'backend',
                'type' => ProductType::Downloadable,
                'license' => LicenseType::FreeAttribution,
                'description' => 'A collection of full-stack components for accelerated Laravel development. Build admin panels, dashboards, and more.',
                'demo_url' => 'https://filamentphp.com',
                'price' => 0,
            ],
            [
                'owner' => 'inertiajs',
                'repo' => 'inertia',
                'title' => 'Inertia.js',
                'category' => 'fullstack',
                'type' => ProductType::Downloadable,
                'license' => LicenseType::FreeUnlimited,
                'description' => 'Build single-page apps, without building an API. Create fully client-side rendered applications.',
                'demo_url' => 'https://inertiajs.com',
                'price' => 0,
            ],
            // Some starter templates (free downloadable)
            [
                'owner' => 'shadcn-ui',
                'repo' => 'ui',
                'title' => 'shadcn/ui Components',
                'category' => 'frontend',
                'type' => ProductType::Downloadable,
                'license' => LicenseType::FreeUnlimited,
                'description' => 'Beautifully designed components that you can copy and paste into your apps. Accessible. Customizable. Open Source.',
                'demo_url' => 'https://ui.shadcn.com',
                'price' => 0,
            ],
        ];

        foreach ($repos as $repoData) {
            $this->seedRepo($repoData);
        }

        $this->command->info('Real Git products seeded successfully!');
    }

    private function seedRepo(array $repoData): void
    {
        $owner = $repoData['owner'];
        $repo = $repoData['repo'];

        $this->command->line("  Processing {$owner}/{$repo}...");

        // Fetch repo info from GitHub
        $repoInfo = $this->gitService->getGitHubRepoInfo($owner, $repo);

        if (! $repoInfo) {
            $this->command->warn("    Could not fetch repo info for {$owner}/{$repo}, skipping...");

            return;
        }

        // Fetch releases
        $latestRelease = $this->gitService->getLatestGitHubRelease($owner, $repo);

        // Create product
        $product = Product::updateOrCreate(
            ['slug' => "{$owner}-{$repo}"],
            [
                'title' => $repoData['title'],
                'short_description' => substr($repoInfo['description'] ?? $repoData['description'], 0, 160),
                'description' => $repoData['description'],
                'content' => $this->buildContent($repoInfo, $latestRelease),
                'image' => "https://opengraph.githubassets.com/1/{$owner}/{$repo}",
                'price' => $repoData['price'] ?? 0,
                'category' => $repoData['category'],
                'type' => $repoData['type'],
                'demo_url' => $repoData['demo_url'] ?? null,
                'github_url' => $repoInfo['html_url'],
                'documentation_url' => $repoData['demo_url'] ?? $repoInfo['html_url'],
                'version' => $latestRelease['tag_name'] ?? $repoInfo['default_branch'],
                'downloads' => 0,
                'rating' => min(5.0, 3.5 + ($repoInfo['stargazers_count'] / 10000)),
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => $repoInfo['stargazers_count'] > 10000,
                'requires_auth' => false,
                'default_license' => $repoData['license'],
                'meta' => [
                    'github_stars' => $repoInfo['stargazers_count'],
                    'github_forks' => $repoInfo['forks_count'],
                    'language' => $repoInfo['language'],
                    'topics' => $repoInfo['topics'],
                    'license' => $repoInfo['license'],
                ],
            ]
        );

        // Create product source (download URL)
        $version = $latestRelease['tag_name'] ?? $repoInfo['default_branch'];
        $downloadUrl = $latestRelease['zipball_url']
            ?? "https://github.com/{$owner}/{$repo}/archive/refs/heads/{$repoInfo['default_branch']}.zip";

        ProductSource::updateOrCreate(
            [
                'product_id' => $product->id,
                'provider' => SourceProvider::GitHub,
            ],
            [
                'name' => $latestRelease ? "Release {$version}" : "Source ({$version})",
                'description' => $latestRelease
                    ? 'Official release from GitHub'
                    : "Latest source code from {$repoInfo['default_branch']} branch",
                'source_url' => $downloadUrl,
                'version' => $version,
                'file_name' => "{$repo}-{$version}.zip",
                'metadata' => [
                    'owner' => $owner,
                    'repo' => $repo,
                    'release_name' => $latestRelease['name'] ?? null,
                    'release_body' => $latestRelease['body'] ?? null,
                    'default_branch' => $repoInfo['default_branch'],
                ],
                'is_primary' => true,
                'is_active' => true,
                'last_verified_at' => now(),
            ]
        );

        // If release has assets, add them as additional sources
        if ($latestRelease && ! empty($latestRelease['assets'])) {
            foreach ($latestRelease['assets'] as $index => $asset) {
                ProductSource::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'provider' => SourceProvider::GitHub,
                        'file_name' => $asset['name'],
                    ],
                    [
                        'name' => $asset['name'],
                        'description' => "Release asset: {$asset['name']}",
                        'source_url' => $asset['download_url'],
                        'version' => $version,
                        'file_size' => $asset['size'],
                        'metadata' => [
                            'content_type' => $asset['content_type'],
                        ],
                        'is_primary' => false,
                        'is_active' => true,
                        'last_verified_at' => now(),
                    ]
                );
            }
        }

        $this->command->info("    Created: {$product->title} (v{$version})");
    }

    private function buildContent(array $repoInfo, ?array $release): string
    {
        $content = "<h2>About</h2>\n";
        $content .= "<p>{$repoInfo['description']}</p>\n\n";

        $content .= "<h2>Statistics</h2>\n";
        $content .= "<ul>\n";
        $content .= '<li><strong>Stars:</strong> '.number_format($repoInfo['stargazers_count'])."</li>\n";
        $content .= '<li><strong>Forks:</strong> '.number_format($repoInfo['forks_count'])."</li>\n";
        $content .= "<li><strong>Language:</strong> {$repoInfo['language']}</li>\n";
        if ($repoInfo['license']) {
            $content .= "<li><strong>License:</strong> {$repoInfo['license']}</li>\n";
        }
        $content .= "</ul>\n\n";

        if ($release) {
            $content .= "<h2>Latest Release</h2>\n";
            $content .= "<p><strong>Version:</strong> {$release['tag_name']}</p>\n";
            if ($release['body']) {
                $content .= "<div>{$release['body']}</div>\n";
            }
        }

        if (! empty($repoInfo['topics'])) {
            $content .= "\n<h2>Topics</h2>\n";
            $content .= '<p>'.implode(', ', $repoInfo['topics'])."</p>\n";
        }

        return $content;
    }

    /**
     * Seed a private GitHub repository (requires GITHUB_TOKEN in .env)
     */
    public function seedPrivateRepo(
        string $owner,
        string $repo,
        string $title,
        string $description,
        string $category = 'private',
        ProductType $type = ProductType::Downloadable,
        float $price = 0.00
    ): ?Product {
        $token = config('services.github.token');

        if (! $token) {
            $this->command?->error('GITHUB_TOKEN not configured in .env - cannot seed private repo');

            return null;
        }

        $this->command?->line("  Processing private repo {$owner}/{$repo}...");

        // Fetch repo info with token
        $repoInfo = $this->gitService->getGitHubRepoInfo($owner, $repo, $token);

        if (! $repoInfo) {
            $this->command?->warn("    Could not fetch repo info for {$owner}/{$repo}");

            return null;
        }

        // Verify it's private
        if (! ($repoInfo['is_private'] ?? false)) {
            $this->command?->warn("    Warning: {$owner}/{$repo} is not a private repo");
        }

        // Fetch releases with token
        $latestRelease = $this->gitService->getLatestGitHubRelease($owner, $repo, $token);

        // Create product
        $product = Product::updateOrCreate(
            ['slug' => "{$owner}-{$repo}"],
            [
                'title' => $title,
                'short_description' => substr($repoInfo['description'] ?? $description, 0, 160),
                'description' => $description,
                'content' => $this->buildContent($repoInfo, $latestRelease),
                'image' => null, // Private repos don't have public OG images
                'price' => $price,
                'category' => $category,
                'type' => $type,
                'github_url' => null, // Don't expose private repo URL
                'documentation_url' => null,
                'version' => $latestRelease['tag_name'] ?? $repoInfo['default_branch'],
                'downloads' => 0,
                'rating' => 4.5,
                'status' => PublishableStatusCast::PUBLISHED,
                'featured' => false,
                'requires_auth' => $price > 0, // Require auth for paid products
                'default_license' => $price > 0 ? LicenseType::Commercial : LicenseType::FreeUnlimited,
                'meta' => [
                    'is_private' => true,
                    'language' => $repoInfo['language'],
                    'license' => $repoInfo['license'],
                ],
            ]
        );

        // Create product source with version from release
        $version = $latestRelease['tag_name'] ?? $repoInfo['default_branch'];

        // For private repos, use API zipball URL
        $downloadUrl = "https://api.github.com/repos/{$owner}/{$repo}/zipball/{$version}";

        ProductSource::updateOrCreate(
            [
                'product_id' => $product->id,
                'provider' => SourceProvider::GitHub,
                'version' => $version,
            ],
            [
                'name' => $latestRelease ? "Release {$version}" : "Source ({$version})",
                'description' => $latestRelease
                    ? 'Official release from private GitHub repository'
                    : "Latest source from {$repoInfo['default_branch']} branch",
                'source_url' => $downloadUrl,
                'file_name' => "{$repo}-{$version}.zip",
                'metadata' => [
                    'owner' => $owner,
                    'repo' => $repo,
                    'is_private' => true,
                    'release_name' => $latestRelease['name'] ?? null,
                    'default_branch' => $repoInfo['default_branch'],
                ],
                'is_primary' => true,
                'is_active' => true,
                'last_verified_at' => now(),
            ]
        );

        $this->command?->info("    Created private product: {$product->title} (v{$version})");

        return $product;
    }
}
