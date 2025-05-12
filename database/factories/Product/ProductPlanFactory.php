<?php

namespace Database\Factories\Product;

use App\Models\Enums\Product\ProductSupportLevelCast;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\ProductPlan>
 */
class ProductPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'product_id' => fake()->randomElement(Product::pluck('id')->toArray()),
            'name' => fake()->unique()->words(3, true), // Unique name for the subscription plan
            'desc' => fake()->text(150), // Concise description
            'price' => fake()->randomFloat(2, 10, 5000), // Pricing in a reasonable range

            'api_call_limit' => fake()->randomElement([0,10000,400000,100000]), // 0 means unlimited
            'records_limit' => fake()->randomElement([0, fake()->numberBetween(1, 10000)]), // 0 means unlimited
            'storage_limit' => fake()->randomElement([0, fake()->numberBetween(1, 10000)]), // 0 means unlimited

            'is_recommended' => fake()->boolean(), // Plan recommendation flag
            'is_enterprise' => fake()->boolean(), // Enterprise-specific flag
            'is_visible' => fake()->boolean(), // Visibility for the front-end
            'has_support' => $hasSupport = fake()->boolean(),
            'support_level' => $hasSupport ? fake()->randomElement(ProductSupportLevelCast::cases()) : null, // Support tiers
        ];
    }

}
