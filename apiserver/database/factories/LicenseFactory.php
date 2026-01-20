<?php

namespace Database\Factories;

use App\Enums\LicenseType;
use App\Models\License;
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
            'license_key' => $this->faker->unique()->sha256(),
            'license_type' => $this->faker->randomElement(LicenseType::cases()),
            'email' => $this->faker->email(),
            'usage_terms' => ['commercial' => false, 'attribution_required' => true],
            'attribution_text' => ['template' => 'Built with [Product Name]'],
            'usage_count' => 0,
            'max_usage' => null,
            'api_config' => null,
            'expires_at' => null,
            'is_active' => true,
            'first_used_at' => null,
            'last_used_at' => null,
        ];
    }
}
