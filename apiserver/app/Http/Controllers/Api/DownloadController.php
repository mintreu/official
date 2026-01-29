<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Products\ProductSource;
use App\Services\DownloadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function __construct(
        private DownloadService $downloadService
    ) {}

    /**
     * Initiate download for a product
     * POST /api/products/{slug}/download
     *
     * Returns a temporary secure download URL
     */
    public function initiate(Request $request, string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->with('activeSources')
            ->firstOrFail();

        // Validate download permission
        $canDownload = $this->downloadService->canDownload($product, $request->user());

        if (! $canDownload['allowed']) {
            return response()->json([
                'error' => $canDownload['reason'] ?? 'Download not allowed',
                'requires_auth' => $product->requires_auth && ! $request->user(),
                'requires_purchase' => ! $product->isFree(),
            ], 403);
        }

        // Get source to download
        $sourceId = $request->input('source_id');
        $source = $sourceId
            ? $product->activeSources()->find($sourceId)
            : $product->getPrimarySource();

        if (! $source) {
            return response()->json([
                'error' => 'No download source available for this product',
            ], 404);
        }

        // Generate secure download URL
        $downloadData = $this->downloadService->generateDownloadUrl(
            $source,
            $request->user(),
            $canDownload['license'] ?? null
        );

        return response()->json([
            'download_url' => $downloadData['url'],
            'expires_at' => $downloadData['expires_at'],
            'file_name' => $source->file_name,
            'file_size' => $source->file_size,
        ]);
    }

    /**
     * Execute download with token
     * GET /api/download/{token}
     *
     * Redirects to actual download URL
     */
    public function download(string $token): RedirectResponse|JsonResponse
    {
        $result = $this->downloadService->validateAndGetDownloadUrl($token);

        if (! $result) {
            return response()->json([
                'error' => 'Invalid or expired download link',
            ], 403);
        }

        // Redirect to actual download URL
        return redirect()->away($result['redirect_url']);
    }

    /**
     * List available sources for a product
     * GET /api/products/{slug}/sources
     */
    public function sources(string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->with('activeSources')
            ->firstOrFail();

        $sources = $product->activeSources->map(fn (ProductSource $source) => [
            'id' => $source->id,
            'name' => $source->name,
            'description' => $source->description,
            'version' => $source->version,
            'file_name' => $source->file_name,
            'file_size' => $source->file_size,
            'file_size_formatted' => $source->file_size
                ? $this->formatBytes($source->file_size)
                : null,
            'is_primary' => $source->is_primary,
            'provider' => $source->provider->getLabel(),
        ]);

        return response()->json([
            'data' => $sources,
        ]);
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision).' '.$units[$pow];
    }
}
