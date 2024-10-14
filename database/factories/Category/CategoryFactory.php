<?php

namespace Database\Factories\Category;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->word();
        return [
            'name' => ucfirst($name),                           // Random category name
            'url' => Str::slug($name),                          // Generate URL slug from name
            'parent_id' => null,                                // Default to null (no parent)
            'short_desc' => $this->faker->sentence(),           // Random short description
            'desc' => $this->faker->paragraph(),                // Random long description
            'status' => $this->faker->boolean(80),              // 80% chance of being active
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
