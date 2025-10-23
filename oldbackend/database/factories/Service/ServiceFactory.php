<?php

namespace Database\Factories\Service;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $name = fake()->unique()->text(16),
            'url' => Str::slug($name),
            'short_desc' => fake()->text(200),
            'desc' => fake()->text(600),
            'status' => fake()->boolean,
//            'order',
        ];
    }
}
