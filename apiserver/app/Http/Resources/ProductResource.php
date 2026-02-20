<?php

namespace App\Http\Resources;

use App\Enums\ProductType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $engagement = $this->engagement;
        $downloads = $engagement?->downloads ?? (int) $this->downloads;
        $rating = $engagement?->rating ?? (float) $this->rating;
        $version = $engagement?->version ?? $this->version;

        $data = [
            'slug' => $this->slug,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'image' => $this->image,
            'price' => (float) $this->price_major,
            'price_paise' => (int) $this->price,
            'category' => $this->category,
            'type' => $this->type?->value,
            'type_label' => $this->type?->getLabel(),
            'demo_url' => $this->demo_url,
            'documentation_url' => $this->documentation_url,
            'version' => $version,
            'downloads' => $downloads,
            'rating' => $rating,
            'featured' => (bool) $this->featured,
            'requires_auth' => (bool) $this->requires_auth,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'engagement' => [
                'downloads' => $downloads,
                'rating' => $rating,
                'version' => $version,
            ],
        ];

        // Only show GitHub URL for freebies (backlink)
        if ($this->type === ProductType::Freebie) {
            $data['github_url'] = $this->github_url;
        }

        // Include content only for detail view (when loaded with slug)
        if ($request->route('slug')) {
            $data['content'] = $this->content;
            $data['meta'] = $this->meta;

            // Load plans for API products
            if ($this->type?->hasPlans()) {
                $data['plans'] = $this->whenLoaded('plans', function () {
                    return $this->activePlans->map(fn ($plan) => [
                        'id' => $plan->id,
                        'slug' => $plan->slug,
                        'name' => $plan->name,
                        'description' => $plan->description,
                        'price' => $plan->price,
                        'price_formatted' => $plan->getFormattedPrice(),
                        'billing_cycle' => $plan->billing_cycle,
                        'billing_label' => $plan->getBillingLabel(),
                        'requests_per_month' => $plan->requests_per_month,
                        'requests_per_day' => $plan->requests_per_day,
                        'requests_per_minute' => $plan->requests_per_minute,
                        'features' => $plan->features,
                        'limits' => $plan->limits,
                        'is_popular' => $plan->is_popular,
                    ]);
                });
            }

            // Load sources for downloadable products
            if ($this->type?->usesSecureDownload()) {
                $data['sources'] = $this->whenLoaded('sources', function () {
                    return $this->activeSources->map(fn ($source) => [
                        'name' => $source->name,
                        'description' => $source->description,
                        'version' => $source->version,
                        'file_name' => $source->file_name,
                        'file_size' => $source->getFileSizeFormatted(),
                        'is_primary' => $source->is_primary,
                    ]);
                });
            }
        }

        return $data;
    }
}
