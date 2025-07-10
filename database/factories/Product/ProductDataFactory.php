<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product\ProductData>
 */
class ProductDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'metadata' => json_encode(['key' => $this->faker->word]),
            'demo_accounts' => $this->getDemoAccounts(),
        ];
    }


    private function getDemoAccounts(): array
    {
        $accounts = [
            'admin' => [
                'email' => 'admin@example.com',
                'password' => 'admin123',
            ],
            'developer' => [
                'email' => 'dev@example.com',
                'password' => 'dev123',
            ],
        ];

        $defaultAccount = [
            'user' => [
                'email' => 'user@example.com',
                'password' => 'user123',
            ],
        ];

        return  array_merge($accounts,$defaultAccount,[

            'desc' => $this->faker->text(600),

        ]);


    }



}
