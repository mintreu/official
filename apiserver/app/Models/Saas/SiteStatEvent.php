<?php

namespace App\Models\Saas;

use Illuminate\Database\Eloquent\Model;

class SiteStatEvent extends Model
{
    protected $table = 'saas_site_stat_events';

    protected $fillable = [
        'source_project',
        'vendor_id',
        'site_id',
        'site_uuid',
        'site_slug',
        'window_start',
        'window_end',
        'metrics',
        'payload',
        'received_at',
    ];

    protected function casts(): array
    {
        return [
            'vendor_id' => 'integer',
            'site_id' => 'integer',
            'window_start' => 'datetime',
            'window_end' => 'datetime',
            'metrics' => 'array',
            'payload' => 'array',
            'received_at' => 'datetime',
        ];
    }
}
