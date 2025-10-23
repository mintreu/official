<?php

namespace Database\Factories\Studio;

use App\Models\Product\Product;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudioFactory extends Factory
{
    public function definition(): array
    {
        $product = Product::with('plans')->inRandomOrder()->find(1);
        return [
            'name' => $name = $this->faker->company,
            'url' => Str::slug($name),
            'domain' => $this->faker->domainName,
            'key' => Str::random(16),
            'secret' => Str::random(32),
            'serial' => strtoupper(Str::random(20)),
            'expire_on' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'is_active' => true,
            'is_trial' => $this->faker->boolean,
            'trial_ends_at' => $this->faker->optional()->dateTimeBetween('now', '+14 days'),
            'channel' => $this->faker->randomElement(['api', 'standard', 'all_in_one']),
            'version' => $this->faker->semver(),
            'platform' => $this->faker->randomElement(['Windows', 'macOS', 'iOS', 'Android', 'Linux']),

            'host_id' => 1, // override in test using `for($user, 'host')`
            'host_type' => 'App\\Models\\User', // default morph class, override as needed

            'product_id' => $product->id,
            'plan_id' => $product->plans->first()->id,
            'subscription_id' => Subscription::factory(),

            'metadata' => ['theme' => 'dark', 'env' => 'production'],
        ];
    }
}
