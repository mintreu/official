<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'project_type' => $this->project_type,
            'project_type_label' => $this->projectTypeLabel,
            'budget' => $this->budget,
            'budget_label' => $this->budgetLabel,
            'message' => $this->message,
            'status' => $this->status,
            'formatted_status' => $this->formatted_status,
            'is_archived' => $this->is_archived,
            'replied_at' => $this->replied_at?->toISOString(),
            'notes' => $this->notes,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
