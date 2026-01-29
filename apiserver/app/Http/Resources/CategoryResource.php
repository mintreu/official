<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'parent' => $this->whenLoaded('parent', fn () => new static($this->parent)),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
            'pivot' => $this->whenPivotLoaded('categoryable', fn () => $this->pivot),
        ];
    }
}
