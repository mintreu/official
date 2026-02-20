<?php

namespace App\Models\Saas;

use App\Models\Licensing\License;
use App\Models\Product;
use App\Models\Products\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicensedSite extends Model
{
    protected $fillable = [
        'user_id',
        'license_id',
        'product_id',
        'plan_id',
        'source_project',
        'vendor_id',
        'vendor_uuid',
        'vendor_slug',
        'vendor_name',
        'site_id',
        'site_uuid',
        'site_slug',
        'site_name',
        'site_domain',
        'site_license_key',
        'site_license_secret',
        'status',
        'provisioned_at',
        'last_seen_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'license_id' => 'integer',
            'product_id' => 'integer',
            'plan_id' => 'integer',
            'vendor_id' => 'integer',
            'site_id' => 'integer',
            'provisioned_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'site_license_secret' => 'encrypted',
            'meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
