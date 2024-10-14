<?php

namespace App\Models\Studio;

use App\Models\Product\Product;
use App\Models\Subscription\Plan;
use App\Models\Subscription\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Studio extends Model
{
    use HasFactory;



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'domain_schema',
        'domain',
        'expire_on',
        'serial_key',
        'user_id',
        'product_id',
        'plan_id',
        'subscription_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expire_on' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     * Automatically generate a unique ULID when a new Vendor is created.
     */
    protected static function booted()
    {
        // Ensure the parent booted method is called first
        parent::booted();

        // Assign ULID to the uuid column when creating a vendor
        static::creating(function ($studio) {
            if (empty($studio->url)) {
                $studio->url = Str::ulid();
            }
        });
    }

    /**
     * Get the user associated with the studio.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product associated with the studio.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the plan associated with the studio.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the subscription associated with the studio.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Check if the subscription has expired.
     *
     * @return bool
     */
    public function hasExpired(): bool
    {
        return $this->expire_on ? Carbon::now()->greaterThan($this->expire_on) : false;
    }

    /**
     * Deactivate the studio if the subscription has expired.
     */
    public function deactivateIfExpired(): void
    {
        if ($this->hasExpired()) {
            $this->update([
                'serial_key' => null, // You may want to reset serial key or handle differently
            ]);
        }
    }

    /**
     * Get the full domain URL for the studio.
     *
     * @return string
     */
    public function getFullDomainAttribute(): string
    {
        return $this->domain_schema . $this->domain;
    }






}
