<?php

namespace App\Models\Saas;

use Illuminate\Database\Eloquent\Model;

class SaasSyncLog extends Model
{
    protected $fillable = [
        'project_slug',
        'direction',
        'action',
        'status',
        'http_status',
        'target_url',
        'message',
        'request_payload',
        'response_payload',
        'executed_at',
    ];

    protected function casts(): array
    {
        return [
            'http_status' => 'integer',
            'request_payload' => 'array',
            'response_payload' => 'array',
            'executed_at' => 'datetime',
        ];
    }
}
