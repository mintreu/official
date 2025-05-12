<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\ProductFlat>
 */
class ProductFlatFactory extends Factory
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
            'short_desc' => fake()->text(200),
            'desc' => fake()->text(500),
            'host_url' => fake()->url(),
            'host_api_url' => fake()->url(),
            'client_login_url' => fake()->url(),
            'demo_accounts' => fake()->randomElements(['admin', 'user', 'guest'])
        ];
    }
}
