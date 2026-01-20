<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CaseStudyResource;
use App\Models\CaseStudy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CaseStudyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $category = $request->string('category')->toString();
        $perPage = (int)($request->input('per_page', 6));
        $query = CaseStudy::query();

        // Only show published case studies
        $query->where('status', 'Published');

        if ($category !== '' && $category !== 'All') {
            $query->where(function ($q) use ($category) {
                $q->where('title', 'like', '%' . $category . '%')
                    ->orWhere('challenge', 'like', '%' . $category . '%')
                    ->orWhere('solution', 'like', '%' . $category . '%');
            });
        }

        $query->latest();

        $paginator = $query->paginate($perPage);
        $paginator->getCollection()->transform(function ($item) {
            return (new CaseStudyResource($item))->toArray(request());
        });

        return response()->json($paginator);
    }

    public function show(string $slug): JsonResponse
    {
        $caseStudy = CaseStudy::where('slug', $slug)->firstOrFail();

        // Get related case studies (same industry, excluding current)
        $relatedCaseStudies = CaseStudy::where('industry', $caseStudy->industry)
            ->where('id', '!=', $caseStudy->id)
            ->where('status', 'Published')
            ->latest()
            ->limit(3)
            ->get();

        return response()->json([
            'data' => (new CaseStudyResource($caseStudy))->toArray(request()),
            'related' => CaseStudyResource::collection($relatedCaseStudies)->toArray(request()),
        ]);
    }
}

