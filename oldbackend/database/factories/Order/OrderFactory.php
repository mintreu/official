<?php

namespace Database\Factories\Order;

use App\Models\Product\Product;
use App\Models\Subscription\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(1000, 5000);
        $discount = $this->faker->numberBetween(0, 500);
        $tax = $this->faker->numberBetween(100, 300);
        $total = $subtotal - $discount + $tax;

        return [
            'uuid' => (string) Str::uuid(),
            'amount' => $total,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'quantity' => $this->faker->numberBetween(1, 5),
            'voucher' => $this->faker->optional()->word,
            'tracking_id' => $this->faker->optional()->uuid,
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'payment_success' => $this->faker->boolean(),
            'expire_at' => $this->faker->dateTimeBetween('now', '+1 month'),
//            'user_id' => User::find(1)->id,
//            'plan_id' => Plan::find(1)->id,
//            'product_id' => Product::find(1)->id,
        ];

    }
}
