<?php

namespace Database\Seeders;

use App\Models\Category\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $allCategoryNames = ['Widget','Template','Game','Apps','Plugins','Image','Video'];

        foreach ($allCategoryNames as $name)
        {
            $newCategory = Category::factory()->create(['name' => $name]);
        }


    }
}
