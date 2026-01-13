<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'image' => $this->image,
            'price' => (float) $this->price,
            'category' => $this->category,
            'type' => $this->type,
            'download_url' => $this->download_url,
            'demo_url' => $this->demo_url,
            'github_url' => $this->github_url,
            'documentation_url' => $this->documentation_url,
            'version' => $this->version,
            'downloads' => (int) $this->downloads,
            'rating' => (float) $this->rating,
            'status' => $this->status,
            'featured' => (bool) $this->featured,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

