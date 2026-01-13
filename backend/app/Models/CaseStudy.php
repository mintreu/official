<?php

namespace App\Models;

use App\Casts\PublishableStatusCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CaseStudy extends Model
{
    use HasFactory;

    protected $table = 'case_studies';

    protected $fillable = [
        'slug',
        'title',
        'description',
        'content',
        'image',
        'client',
        'industry',
        'duration',
        'technologies',
        'challenge',
        'solution',
        'results',
        'status',
        'featured',
    ];

    protected $casts = [
        'technologies' => 'array',
        'featured' => 'boolean',
        'status' => PublishableStatusCast::class
    ];

    protected static function booted(): void
    {
        static::creating(function (CaseStudy $caseStudy) {
            if (empty($caseStudy->slug)) {
                $caseStudy->slug = Str::slug($caseStudy->title);
            }
        });
    }
}
