<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create unique categories from legacy columns across models
        $legacyData = [
            ['table' => 'articles', 'column' => 'category', 'model' => 'App\\Models\\Content\\Article'],
            ['table' => 'projects', 'column' => 'category', 'model' => 'App\\Models\\Content\\Project'],
            ['table' => 'products', 'column' => 'category', 'model' => 'App\\Models\\Product'],
            // case_studies has no category column
        ];

        foreach ($legacyData as $data) {
            $uniqueCategories = DB::table($data['table'])
                ->whereNotNull($data['column'])
                ->where($data['column'], '!=', '')
                ->distinct()
                ->pluck($data['column']);

            foreach ($uniqueCategories as $name) {
                $slug = Str::slug($name);
                Category::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $name, 'is_active' => true, 'order' => 0]
                );
            }
        }

        // Backfill attachments - requires categories() relations defined in models
        foreach ($legacyData as $data) {
            $modelClass = $data['model'];
            $modelClass::whereNotNull($data['column'])
                ->where($data['column'], '!=', '')
                ->chunk(1000, function ($items) use ($data) {
                    foreach ($items as $item) {
                        $slug = Str::slug($item->{$data['column']});
                        $category = Category::where('slug', $slug)->first();
                        if ($category) {
                            $item->categories()->attach($category->id);
                        }
                    }
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally detach categories or drop pivot rows for these types
        DB::table('categoryables')
            ->whereIn('categoryable_type', [
                'App\\Models\\Content\\Article',
                'App\\Models\\Content\\Project',
                'App\\Models\\Product',
            ])
            ->delete();
    }
};
