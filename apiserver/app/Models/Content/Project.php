<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'content',
        'image',
        'technologies',
        'status',
        'featured',
        'live_url',
        'github_url',
    ];

    protected $casts = [
        'technologies' => 'array',
        'featured' => 'boolean',
        'status' => \App\Casts\PublishableStatusCast::class,
    ];

    public function categories(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(\App\Models\Category::class, 'categoryable');
    }

    public function getCategoriesNamesAttribute(): array
    {
        return $this->categories->pluck('name')->toArray();
    }

    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }
}
