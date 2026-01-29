<?php

namespace Database\Factories;

use App\Enums\LicenseType;
use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LicenseFactory extends Factory
{
    protected $model = License::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory(),
            'license_key' => strtoupper($this->faker->unique()->lexify('????-????-????-????')),
            'type' => $this->faker->randomElement(LicenseType::cases()),
            'email' => $this->faker->email(),
            'meta' => ['commercial' => false, 'attribution_required' => true],
            'usage_count' => 0,
            'max_usage' => null,
            'expires_at' => null,
            'is_active' => true,
            'first_used_at' => null,
            'last_used_at' => null,
        ];
    }
}
