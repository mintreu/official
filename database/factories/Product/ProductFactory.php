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
    public function definition(): array
    {
        $name = $this->faker->unique()->word . ' ' . $this->faker->unique()->word;

        return [
            'type' => $type = $this->faker->randomElement(ProductTypeCast::cases())->value,
            'name' => $name,
            'url' => Str::slug($name),
            'short_desc' => $this->faker->text(220),

            'status' => $this->faker->boolean,
            'chargeable' => $this->faker->boolean,

        ];
    }





}
