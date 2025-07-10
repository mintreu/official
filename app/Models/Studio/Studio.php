<?php

namespace App\Models\Studio;

use App\Models\Product\Product;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Subscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Studio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'domain',
        'key',
        'secret',
        'serial',
        'expire_on',
        'is_active',
        'is_trial',
        'trial_ends_at',
        'channel',
        'version',
        'platform',
        'host_id',
        'host_type',
        'product_id',
        'plan_id',
        'subscription_id',
        'metadata',
    ];

    protected $casts = [
        'expire_on' => 'datetime',
        'trial_ends_at' => 'datetime',
        'is_active' => 'boolean',
        'is_trial' => 'boolean',
        'metadata' => 'array',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function ($studio) {
            if (empty($studio->url)) {
                $studio->url = Str::ulid();
            }
        });
    }

    public function host(): MorphTo
    {
        return $this->morphTo();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function hasExpired(): bool
    {
        return $this->expire_on ? Carbon::now()->greaterThan($this->expire_on) : false;
    }

    public function deactivateIfExpired(): void
    {
        if ($this->hasExpired()) {
            $this->update([
                'is_active' => false,
                'serial' => null,
            ]);
        }
    }

    public function getFullDomainAttribute(): string
    {
        return $this->domain_schema . $this->domain;
    }

    public function isInTrial(): bool
    {
        return $this->is_trial && Carbon::now()->lessThanOrEqualTo($this->trial_ends_at);
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->hasExpired();
    }
}
