<?php

namespace App\Models\Subscription;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'duration_in_months',
        'start_date',
        'expiry_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user associated with the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan associated with the subscription.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Calculate the expiry date based on the subscription's start date and duration.
     */
    public function calculateExpiryDate(): void
    {
        if ($this->start_date) {
            $this->expiry_date = $this->start_date->copy()->addMonths($this->duration_in_months);
        }
    }

    /**
     * Scope for active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('expiry_date', '>', now());
    }

    /**
     * Start a subscription and calculate the expiry date.
     */
    public function startSubscription(): void
    {
        $this->start_date = now();
        $this->calculateExpiryDate();
        $this->save();
    }

    /**
     * Check if the subscription is expired.
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && now()->greaterThan($this->expiry_date);
    }

    /**
     * Deactivate a subscription when expired.
     */
    public function deactivateIfExpired(): void
    {
        if ($this->isExpired()) {
            $this->update(['is_active' => false]);
        }
    }
}
