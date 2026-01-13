<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'image' => $this->image,
            'category' => $this->category,
            'technologies' => $this->technologies ?? [],
            'status' => $this->status,
            'featured' => (bool) $this->featured,
            'live_url' => $this->live_url,
            'github_url' => $this->github_url,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

