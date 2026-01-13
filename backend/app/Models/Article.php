<?php

namespace App\Models;

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
        'category',
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

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }
}
