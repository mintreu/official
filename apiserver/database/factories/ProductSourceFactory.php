<?php

namespace Database\Factories;

use App\Enums\SourceProvider;
use App\Models\Product;
use App\Models\Products\ProductSource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductSourceFactory extends Factory
{
    protected $model = ProductSource::class;

    public function definition(): array
    {
        $provider = $this->faker->randomElement([
            SourceProvider::GitHub,
            SourceProvider::GitLab,
            SourceProvider::DirectUrl,
        ]);

        return [
            'product_id' => Product::factory(),
            'provider' => $provider,
            'name' => $this->faker->words(2, true).' Source',
            'description' => $this->faker->optional()->sentence(),
            'source_url' => $this->generateSourceUrl($provider),
            'encrypted_token' => null,
            'version' => 'v'.$this->faker->semver(),
            'file_name' => $this->faker->slug(2).'.zip',
            'file_size' => $this->faker->numberBetween(1024, 50 * 1024 * 1024),
            'metadata' => $this->generateMetadata($provider),
            'is_primary' => $this->faker->boolean(30),
            'is_active' => true,
            'last_verified_at' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * GitHub source
     */
    public function github(): static
    {
        return $this->state(fn (array $attributes) => [
            'provider' => SourceProvider::GitHub,
            'source_url' => 'https://github.com/example/repo/archive/refs/tags/v1.0.0.zip',
            'metadata' => [
                'owner' => 'example',
                'repo' => 'repo',
                'tag' => 'v1.0.0',
            ],
        ]);
    }

    /**
     * GitLab source
     */
    public function gitlab(): static
    {
        return $this->state(fn (array $attributes) => [
            'provider' => SourceProvider::GitLab,
            'source_url' => 'https://gitlab.com/example/repo/-/archive/v1.0.0/repo-v1.0.0.zip',
            'metadata' => [
                'project_id' => $this->faker->numberBetween(1000, 9999),
                'tag' => 'v1.0.0',
            ],
        ]);
    }

    /**
     * Direct URL source (no masking needed)
     */
    public function directUrl(): static
    {
        return $this->state(fn (array $attributes) => [
            'provider' => SourceProvider::DirectUrl,
            'source_url' => $this->faker->url().'/'.$this->faker->slug(2).'.zip',
            'metadata' => null,
        ]);
    }

    /**
     * Private GitHub repo with token
     */
    public function privateGithub(string $owner = 'mintreu', string $repo = 'test-repo'): static
    {
        return $this->state(function (array $attributes) use ($owner, $repo) {
            $source = new ProductSource;

            return [
                'provider' => SourceProvider::GitHub,
                'source_url' => "https://github.com/{$owner}/{$repo}/archive/refs/tags/v1.zip",
                'metadata' => [
                    'owner' => $owner,
                    'repo' => $repo,
                    'tag' => 'v1',
                    'is_private' => true,
                ],
            ];
        });
    }

    /**
     * Primary source (shown first)
     */
    public function primary(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_primary' => true,
        ]);
    }

    /**
     * Inactive source
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * With specific version
     */
    public function version(string $version): static
    {
        return $this->state(fn (array $attributes) => [
            'version' => $version,
        ]);
    }

    /**
     * With auth token (encrypted)
     */
    public function withToken(string $token): static
    {
        return $this->afterCreating(function (ProductSource $source) use ($token) {
            $source->setToken($token);
            $source->save();
        });
    }

    private function generateSourceUrl(SourceProvider $provider): string
    {
        return match ($provider) {
            SourceProvider::GitHub => sprintf(
                'https://github.com/%s/%s/archive/refs/tags/v%s.zip',
                $this->faker->userName(),
                $this->faker->slug(2),
                $this->faker->semver()
            ),
            SourceProvider::GitLab => sprintf(
                'https://gitlab.com/%s/%s/-/archive/v%s/%s-v%s.zip',
                $this->faker->userName(),
                $slug = $this->faker->slug(2),
                $version = $this->faker->semver(),
                $slug,
                $version
            ),
            default => $this->faker->url().'/'.$this->faker->slug(2).'.zip',
        };
    }

    private function generateMetadata(SourceProvider $provider): ?array
    {
        return match ($provider) {
            SourceProvider::GitHub => [
                'owner' => $this->faker->userName(),
                'repo' => $this->faker->slug(2),
                'tag' => 'v'.$this->faker->semver(),
            ],
            SourceProvider::GitLab => [
                'project_id' => $this->faker->numberBetween(1000, 9999),
                'tag' => 'v'.$this->faker->semver(),
            ],
            default => null,
        };
    }
}
