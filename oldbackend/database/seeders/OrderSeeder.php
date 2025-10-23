<?php

namespace Database\Seeders;

use App\Models\Order\Order;
use App\Models\Project\Project;
use App\Models\Subscription\Plan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $demoUser = User::firstWhere('email','user@example.com');

        $plan = Plan::firstWhere('url','standard-plan');

        $project = Project::with('products')->find(1);
        $product = $project->products->first();

        $newOrder = Order::factory()
                        ->create([
                            'user_id' => $demoUser->id,
                            'plan_id' => $plan->id,
                            'product_id' => $product->id,
                        ]);



        // Insert into the user_products table via userProducts() relationship
        $demoUser->userProducts()->create([
            'order_id'   => $newOrder->id,
            'product_id' => $product->id,
        ]);



    }
}
