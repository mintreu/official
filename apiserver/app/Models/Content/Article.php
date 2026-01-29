<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'content',
        'image',
        'tags',
        'author',
        'reading_time',
        'status',
        'featured',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'featured' => 'boolean',
        'published_at' => 'datetime',
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
        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }
}
