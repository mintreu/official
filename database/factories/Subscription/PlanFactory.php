<?php

namespace Database\Factories\Subscription;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'url' => $this->faker->unique()->url,
            'base_price' => $this->faker->randomFloat(2, 0, 100),
            'hsn_code' => $this->faker->optional()->ean8,
            'tax_percent' => $this->faker->randomFloat(2, 0, 100),
            'tax_amount' => $this->faker->randomFloat(2, 0, 100),
            'price' => $this->faker->randomFloat(2, 0, 100),
            'per_month_limit' => $this->faker->optional()->numberBetween(100, 1000),
            'auth_type' => $this->faker->optional()->randomElement(['Basic', 'OAuth 2.0', 'API Keys']),
            'support_type' => $this->faker->optional()->randomElement(['Community', 'Standard', 'Priority', '24/7', 'Dedicated']),
            'documentation_type' => $this->faker->optional()->randomElement(['Limited', 'Basic', 'Enhanced', 'Comprehensive']),
            'custom_features' => $this->faker->boolean,
            'rate_limit' => $this->faker->randomElement(['100', '1,000', '10,000', '50,000', 'Unlimited']),
            'authentication' => $this->faker->randomElement(['Basic', 'OAuth 2.0', 'API Keys', 'Custom']),
            'support' => $this->faker->randomElement(['Community', 'Standard', 'Priority', '24/7', 'Dedicated']),
            'documentation' => $this->faker->randomElement(['Limited', 'Basic', 'Enhanced', 'Comprehensive']),
            'data_security' => $this->faker->randomElement(['Basic', 'Standard', 'Enhanced', 'Advanced', 'Enterprise']),
            'analytics_reporting' => $this->faker->randomElement(['Basic', 'Standard', 'Enhanced', 'Advanced', 'Custom']),
            'plugin_support' => $this->faker->boolean,
            'upgradable' => $this->faker->boolean,
            'metadata' => $this->faker->optional()->json,
        ];
    }
}
