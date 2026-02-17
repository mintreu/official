<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $type = $request->query('type');
        $query = Category::query()->active()->orderBy('order');

        if ($type) {
            $modelClass = match($type) {
                'articles' => \App\Models\Content\Article::class,
                'products' => \App\Models\Product::class,
                'projects' => \App\Models\Content\Project::class,
                'case-studies' => \App\Models\Content\CaseStudy::class,
            };

            if ($modelClass) {
                $query->whereHas('categoryables', fn($q) => $q->where('categoryable_type', $modelClass));
            }
        }

        return CategoryResource::collection($query->get())->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
