<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->string('search')->toString();
        $category = $request->string('category')->toString();
        $type = $request->string('type')->toString();
        $sort = $request->string('sort')->toString() ?: 'latest';
        $perPage = (int) ($request->input('per_page', 12));

        $query = Product::query();

        // Only show published products
        $query->where('status', 'Published');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('content', 'like', '%'.$search.'%');
            });
        }

        if ($category !== '' && $category !== 'All') {
            $query->where('category', 'like', '%'.$category.'%');
        }

        if ($type !== '') {
            if ($type === 'freebie') {
                // Freebies: price = 0 OR type = freebie
                $query->where(function ($q) {
                    $q->where('price', 0)
                        ->orWhere('type', 'freebie');
                });
            } elseif ($type === 'api') {
                // API products: api_service or api_referral
                $query->whereIn('type', ['api_service', 'api_referral']);
            } elseif ($type === 'template') {
                // Downloadable templates
                $query->where('type', 'downloadable');
            } else {
                $query->where('type', $type);
            }
        }

        if ($sort === 'latest') {
            $query->latest();
        } elseif ($sort === 'popular') {
            $query->orderByDesc('downloads');
        } elseif ($sort === 'price_low') {
            $query->orderBy('price');
        } elseif ($sort === 'price_high') {
            $query->orderByDesc('price');
        }

        $query->with('categories');\n        $paginator = $query->paginate($perPage);
        $paginator->getCollection()->transform(function ($item) {
            return (new ProductResource($item))->toArray(request());
        });

        return response()->json($paginator);
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->with(['plans' => fn ($q) => $q->active()->ordered()])
            ->with(['sources' => fn ($q) => $q->active()->ordered()])
            ->firstOrFail();

        // Get related products (same category, excluding current)
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->where('status', 'Published')
            ->latest()
            ->limit(4)
            ->get();

        return response()->json([
            'data' => (new ProductResource($product))->toArray(request()),
            'related' => ProductResource::collection($relatedProducts)->toArray(request()),
        ]);
    }
}
