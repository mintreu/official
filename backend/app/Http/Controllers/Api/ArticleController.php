<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->string('search')->toString();
        $category = $request->string('category')->toString();
        $sort = $request->string('sort')->toString() ?: 'latest';
        $perPage = (int)($request->input('per_page', 12));

        $query = Article::query();

        // Only show published articles
        $query->where('status', 'Published');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('excerpt', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        if ($category !== '' && $category !== 'All') {
            $query->where('category', $category);
        }

        if ($sort === 'latest') {
            $query->orderByDesc('published_at');
        } elseif ($sort === 'popular') {
            $query->orderByDesc('views');
        }

        $paginator = $query->paginate($perPage);
        $paginator->getCollection()->transform(function ($item) {
            return (new ArticleResource($item))->toArray(request());
        });

        return response()->json($paginator);
    }

    public function show(string $slug): JsonResponse
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        // Get related articles (same category, excluding current)
        $relatedArticles = Article::where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->where('status', 'Published')
            ->latest()
            ->limit(3)
            ->get();

        return response()->json([
            'data' => (new ArticleResource($article))->toArray(request()),
            'related' => ArticleResource::collection($relatedArticles)->toArray(request()),
        ]);
    }
}

