<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\ProductEngagement;
use App\Models\Products\ProductReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductReviewController extends Controller
{
    public function my(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'product_slug' => ['required', 'string', Rule::exists('products', 'slug')],
        ]);

        $product = Product::query()->where('slug', $validated['product_slug'])->firstOrFail();
        $review = ProductReview::query()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        return response()->json([
            'data' => $review ? $this->mapReview($review) : null,
        ]);
    }

    public function upsert(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'product_slug' => ['required', 'string', Rule::exists('products', 'slug')],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:2000'],
            'license_uuid' => ['nullable', 'string', Rule::exists('licenses', 'uuid')],
        ]);

        $product = Product::query()->where('slug', $validated['product_slug'])->firstOrFail();

        $license = License::query()
            ->where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->first();

        if (! $license) {
            return response()->json(['message' => 'Only customers with an active license can submit a review.'], 422);
        }

        $review = ProductReview::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $product->id,
            ],
            [
                'license_id' => $license->id,
                'rating' => (int) $validated['rating'],
                'review' => $validated['review'] ?? null,
                'is_published' => true,
            ]
        );

        $this->syncProductRating($product->id);

        return response()->json([
            'message' => 'Review saved successfully.',
            'data' => $this->mapReview($review->fresh()),
        ]);
    }

    private function syncProductRating(int $productId): void
    {
        $avg = ProductReview::query()
            ->where('product_id', $productId)
            ->where('is_published', true)
            ->avg('rating');

        $rating = $avg ? round((float) $avg, 2) : 0.0;

        Product::query()->where('id', $productId)->update(['rating' => $rating]);
        ProductEngagement::query()->updateOrCreate(
            ['product_id' => $productId],
            ['rating' => $rating]
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function mapReview(ProductReview $review): array
    {
        return [
            'uuid' => $review->uuid,
            'rating' => $review->rating,
            'review' => $review->review,
            'updated_at' => $review->updated_at?->toIsoString(),
        ];
    }
}
