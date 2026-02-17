<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdsController extends Controller
{
    private const CACHE_TTL_SECONDS = 120;

    /**
     * Read-only ads endpoint for Nuxt
     * GET /api/ads?zone=header&page=/products
     */
    public function index(Request $request): JsonResponse
    {
        $zone = trim((string) $request->query('zone', ''));
        $page = $request->query('page');

        if ($zone === '') {
            return response()->json([
                'error' => 'zone is required',
            ], 422);
        }

        $placement = $this->mapZoneToPlacement($zone);
        $pageKey = $page ? sha1((string) $page) : 'all';
        $cacheKey = "ads:{$placement}:{$pageKey}";

        $ads = Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use ($placement, $page) {
            $query = Advertisement::query()
                ->where('placement', $placement)
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('starts_at')
                        ->orWhere('starts_at', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', now());
                });

            if ($page) {
                $query->where(function ($q) use ($page) {
                    $q->whereNull('allowed_pages')
                        ->orWhereJsonContains('allowed_pages', $page);
                });
            }

            return $query
                ->orderByDesc('priority')
                ->orderByDesc('created_at')
                ->get();
        });

        return response()->json([
            'data' => $ads->map(fn (Advertisement $ad) => [
                'id' => $ad->id,
                'zone' => $zone,
                'type' => 'html',
                'content' => $ad->html_code,
                'href' => null,
                'priority' => $ad->priority,
                'starts_at' => $ad->starts_at?->toISOString(),
                'ends_at' => $ad->ends_at?->toISOString(),
                'is_active' => $ad->is_active,
            ]),
        ]);
    }

    private function mapZoneToPlacement(string $zone): string
    {
        return match ($zone) {
            'header' => 'ads_top',
            'sidebar' => 'ads_sidebar',
            'bottom' => 'ads_bottom',
            'insights' => 'ads_insights',
            default => $zone,
        };
    }
}
