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
        $perPage = (int)($request->input('per_page', 12));

        $query = Product::query();

        // Only show published products
        $query->where('status', 'Published');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('features', 'like', '%' . $search . '%');
            });
        }

        if ($category !== '' && $category !== 'All') {
            $query->where('category', 'like', '%' . $category . '%');
        }

        if ($type !== '') {
            $query->where('type', $type);
        }

        if ($sort === 'latest') {
            $query->latest();
        } elseif ($sort === 'popular') {
            $query->orderByDesc('downloads');
        } elseif ($sort === 'price_low') {
            $query->orderBy('price_usd')->orderBy('price_inr');
        } elseif ($sort === 'price_high') {
            $query->orderByDesc('price_usd')->orderByDesc('price_inr');
        }

        $paginator = $query->paginate($perPage);
        $paginator->getCollection()->transform(function ($item) {
            return (new ProductResource($item))->toArray(request());
        });

        return response()->json($paginator);
    }

    public function show(string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();

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

