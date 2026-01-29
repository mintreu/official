<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Web Development
            ['name' => 'Web Development', 'slug' => 'web-development', 'parent_id' => null, 'order' => 1],
            ['name' => 'Frontend', 'slug' => 'frontend', 'parent_id' => 1, 'order' => 1],
            ['name' => 'React', 'slug' => 'react', 'parent_id' => 2, 'order' => 1],
            ['name' => 'Nuxt', 'slug' => 'nuxt', 'parent_id' => 2, 'order' => 2],
            ['name' => 'Backend', 'slug' => 'backend', 'parent_id' => 1, 'order' => 2],
            ['name' => 'Laravel', 'slug' => 'laravel', 'parent_id' => 5, 'order' => 1],
            ['name' => 'API', 'slug' => 'api', 'parent_id' => 1, 'order' => 3],

            // Tools
            ['name' => 'Tools', 'slug' => 'tools', 'parent_id' => null, 'order' => 2],
            ['name' => 'DevOps', 'slug' => 'devops', 'parent_id' => 8, 'order' => 1],
            ['name' => 'Docker', 'slug' => 'docker', 'parent_id' => 9, 'order' => 1],

            // Freebies
            ['name' => 'Freebies', 'slug' => 'freebies', 'parent_id' => null, 'order' => 3],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // Update parent_ids with actual IDs
        $web = \App\Models\Category::where('slug', 'web-development')->first();
        $frontend = \App\Models\Category::where('slug', 'frontend')->first();
        $react = \App\Models\Category::where('slug', 'react')->first();
        $nuxt = \App\Models\Category::where('slug', 'nuxt')->first();
        $backend = \App\Models\Category::where('slug', 'backend')->first();
        $laravel = \App\Models\Category::where('slug', 'laravel')->first();
        $api = \App\Models\Category::where('slug', 'api')->first();

        $frontend->update(['parent_id' => $web->id]);
        $react->update(['parent_id' => $frontend->id]);
        $nuxt->update(['parent_id' => $frontend->id]);
        $backend->update(['parent_id' => $web->id]);
        $laravel->update(['parent_id' => $backend->id]);
        $api->update(['parent_id' => $web->id]);
    }
}
