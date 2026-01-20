<?php

namespace Database\Factories;

use App\Casts\PublishableStatusCast;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->slug(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'content' => $this->faker->paragraphs(3, true),
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->numberBetween(0, 100),
            'category' => $this->faker->randomElement(['frontend', 'backend', 'fullstack', 'games', 'assets', 'templates']),
            'type' => $this->faker->randomElement(['template', 'game', 'package', 'media', 'api', 'plugin', 'assets']),
            'demo_url' => $this->faker->url(),
            'github_url' => $this->faker->optional()->url(),
            'documentation_url' => $this->faker->optional()->url(),
            'version' => $this->faker->semver(),
            'downloads' => $this->faker->numberBetween(0, 10000),
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'status' => PublishableStatusCast::PUBLISHED,
            'featured' => $this->faker->boolean(30),
            'is_payable' => $this->faker->boolean(50),
            'requires_account' => $this->faker->boolean(20),
            'default_license_type' => 'FREE_ATTRIBUTION',
        ];
    }
}
