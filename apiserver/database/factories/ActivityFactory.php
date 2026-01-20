<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => $this->faker->optional(0.5)->randomElement(User::pluck('id')),
            'action_type' => $this->faker->randomElement(['view', 'download', 'share', 'click']),
            'ip_address' => $this->faker->ipv4(),
            'metadata' => $this->faker->optional(0.5)->words(3, true),
        ];
    }
}
