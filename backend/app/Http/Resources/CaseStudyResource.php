<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseStudyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'image' => $this->image,
            'client' => $this->client,
            'industry' => $this->industry,
            'duration' => $this->duration,
            'technologies' => $this->technologies ?? [],
            'challenge' => $this->challenge,
            'solution' => $this->solution,
            'results' => $this->results,
            'status' => $this->status,
            'featured' => (bool) $this->featured,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

