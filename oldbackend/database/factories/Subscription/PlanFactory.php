<?php

namespace Database\Factories\Subscription;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlanFactory extends Factory
{
    public function definition(): array
    {
        $product = Product::factory()->create();

        return [
            'name' => $name = $this->faker->randomElement(['Free','Standard','Pro','Enterprise']) .' '.Str::limit($product->name,10),
            'url' => Str::slug($name),
            'desc' => $this->faker->optional()->sentence(),
            'base_price' => $this->faker->randomFloat(2, 0, 100),
            'hsn_code' => $this->faker->optional()->ean8(),
            'tax_percent' => $this->faker->randomFloat(2, 0, 100),
            'tax_amount' => $this->faker->randomFloat(2, 0, 100),
            'price' => $this->faker->randomFloat(2, 0, 100),
            'per_month_limit' => $this->faker->optional()->numberBetween(100, 1000),
            'auth_type' => $this->faker->optional()->randomElement(['Basic', 'OAuth 2.0', 'API Keys']),
            'support_type' => $this->faker->optional()->randomElement(['Community', 'Standard', 'Priority']),
            'documentation_type' => $this->faker->optional()->randomElement(['Limited', 'Basic', 'Enhanced']),
            'features' => $this->faker->optional()->randomElements([
                'analytics', 'priority_support', 'custom_reports', 'white_label', 'multi_user'
            ], rand(1, 3)),
            'is_recommended' => $this->faker->boolean(),
            'is_enterprise' => $this->faker->boolean(),
            'visible_on_front' => $this->faker->boolean(),
            'product_id' => $product->id,
        ];
    }
}
