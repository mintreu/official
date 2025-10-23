<?php

namespace Database\Seeders;

use App\Models\Enums\ProductTypeCast;
use App\Models\Product\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $firstProduct = Product::factory()->hasData()->hasPlans(3)->create([
            'name' => $name = 'Accountant Team',
            'type' => ProductTypeCast::API,
            'url' => Str::slug($name),
            'short_desc' => implode('<br>',fake()->paragraphs(1)),
        ]);

        $secondProduct = Product::factory()->hasData()->hasPlans(3)->create([
            'name' => $name = 'Accountant',
            'type' => ProductTypeCast::STANDARD,
            'url' => Str::slug($name),
            'short_desc' => implode('<br>',fake()->paragraphs(1)),
        ]);

        $thirdProduct = Product::factory()->hasData()->hasPlans(3)->create([
            'name' => $name = 'Podcast Team',
            'type' => ProductTypeCast::API,
            'url' => Str::slug($name),
            'short_desc' => implode('<br>',fake()->paragraphs(1)),
        ]);

        $fourthProduct = Product::factory()->hasData()->hasPlans(3)->create([
            'name' => $name = 'Podcast Lite',
            'type' => ProductTypeCast::STANDARD,
            'url' => Str::slug($name),
            'short_desc' => implode('<br>',fake()->paragraphs(1)),
        ]);



        // Bulk Product

        Product::factory(10)->hasData()->hasPlans(rand(2,4))->create();


    }
}
