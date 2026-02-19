<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Content\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $search = $request->string('search')->toString();
        $category = $request->string('category')->toString();
        $sort = $request->string('sort')->toString() ?: 'latest';
        $perPage = (int) ($request->input('per_page', 9));

        $query = Project::query();

        // Only show published projects
        $query->where('status', 'Published');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($category !== '' && $category !== 'All') {
            $query->where('category', 'like', '%'.$category.'%');
        }

        if ($sort === 'latest') {
            $query->latest();
        }

        $paginator = $query->paginate($perPage);
        $paginator->getCollection()->transform(function ($item) {
            return (new ProjectResource($item))->toArray(request());
        });

        return response()->json($paginator);
    }

    public function show(string $slug): JsonResponse
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        // Get related projects (same category, excluding current)
        $relatedProjects = Project::where('category', $project->category)
            ->where('id', '!=', $project->id)
            ->where('status', 'Published')
            ->latest()
            ->limit(3)
            ->get();

        return response()->json([
            'data' => (new ProjectResource($project))->toArray(request()),
            'related' => ProjectResource::collection($relatedProjects)->toArray(request()),
        ]);
    }
}

