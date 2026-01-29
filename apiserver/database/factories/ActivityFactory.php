<?php

namespace Database\Factories;

use App\Models\Content\Activity;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => null,
            'action_type' => $this->faker->randomElement(['view', 'download', 'share', 'click']),
            'ip_address' => $this->faker->ipv4(),
            'metadata' => [],
        ];
    }
}
