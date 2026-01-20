<?php

namespace App\Http\Controllers\Api;

use App\Models\Activity;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ActivityController extends Controller
{
    public function recordShare(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'integer|exists:products,id',
            'platform' => 'required|string|max:50',
            'url' => 'required|url',
        ]);

        try {
            Activity::recordShare(
                product: Product::findOrFail($validated['product_id'] ?? 0) ?? null,
                user: $request->user(),
                platform: $validated['platform'],
                ipAddress: $request->ip() ?? ''
            );

            return response()->json([
                'success' => true,
                'message' => 'Share activity recorded',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record share activity',
            ], 500);
        }
    }

    public function recordView(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        try {
            $product = Product::findOrFail($validated['product_id']);

            Activity::recordView(
                product: $product,
                user: $request->user(),
                ipAddress: $request->ip() ?? ''
            );

            return response()->json([
                'success' => true,
                'message' => 'View activity recorded',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record view activity',
            ], 500);
        }
    }

    public function recordDownload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        try {
            $product = Product::findOrFail($validated['product_id']);

            Activity::recordDownload(
                product: $product,
                user: $request->user(),
                ipAddress: $request->ip() ?? ''
            );

            return response()->json([
                'success' => true,
                'message' => 'Download activity recorded',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record download activity',
            ], 500);
        }
    }
}
