<?php

namespace Database\Factories\Subscription;

use App\Models\Subscription\Plan;
use App\Models\Subscription\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', 'now');
        $duration = $this->faker->randomElement([1, 3, 6, 12]);
        $expiryDate = Carbon::parse($startDate)->addMonths($duration);

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'plan_id' => Plan::inRandomOrder()->first()?->id ?? Plan::factory(),
            'duration_in_months' => $duration,
            'start_date' => $startDate,
            'expiry_date' => $expiryDate,
            'is_active' => $expiryDate > now(),
        ];
    }
}
