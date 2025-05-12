<?php

namespace Database\Factories\Product;

use App\Models\Category\Category;
use App\Models\Enums\Product\ProductTypeCast;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement([ProductTypeCast::API,ProductTypeCast::STANDALONE]),
            'name' => fake()->words(3, true),
            'url' => fake()->unique()->url(),
            'status' => fake()->boolean(),
            'popularity' => fake()->numberBetween(0, 100),
            'views' => fake()->numberBetween(0, 10000),
            'featured' => fake()->boolean(),
            'visibility' => fake()->boolean(),

        ];
    }
}

