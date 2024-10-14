<?php

namespace Database\Factories\Product;

use App\Models\Category\Category;
use App\Models\Enums\ProductTypeCast;
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
        $categories = Category::all();

        return [
            'type' => fake()->randomElement(ProductTypeCast::class),
            'name' => $name = fake()->unique()->word.' '.fake()->unique()->word,
            'url' => Str::slug($name),
            'short_desc' => $this->faker->paragraph(6),
            'desc' => $this->faker->paragraph(20),
            'status' => $this->faker->boolean,
            'chargeable' => $this->faker->boolean,
//            'base_price' => $this->faker->randomFloat(2, 0, 1000),
//            'tax_percent' => $this->faker->randomFloat(2, 0, 100),
//            'tax_amount' => $this->faker->randomFloat(2, 0, 100),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'metadata' => json_encode(['key' => $this->faker->word]),
            'category_id' => $categories->random(1)->first->id,
        ];
    }
}

