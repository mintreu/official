<?php

namespace App\Models;

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
        'category',
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

    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }
}
