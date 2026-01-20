<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'name',
        'placement',
        'html_code',
        'allowed_pages',
        'priority',
        'impressions',
        'clicks',
        'unique_ips',
        'viewed_ips',
        'max_impressions_per_ip',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'allowed_pages' => 'json',
        'viewed_ips' => 'json',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'impressions' => 'integer',
        'clicks' => 'integer',
        'unique_ips' => 'integer',
        'max_impressions_per_ip' => 'integer',
    ];

    /**
     * Get active ads for a specific placement
     */
    public function scopeForPlacement(Builder $query, string $placement): Builder
    {
        return $query->where('placement', $placement)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->orderByDesc('priority');
    }

    /**
     * Record impression for an IP address
     */
    public function recordImpression(string $ipAddress): void
    {
        $viewedIps = $this->viewed_ips ?? [];

        // Clean old IPs (keep last 24 hours worth)
        $hoursAgo24 = now()->subHours(24);

        // Initialize IP tracking if needed
        if (! isset($viewedIps[$ipAddress])) {
            $viewedIps[$ipAddress] = [];
        }

        // Clean old timestamps for this IP
        $viewedIps[$ipAddress] = collect($viewedIps[$ipAddress])
            ->filter(fn ($timestamp) => strtotime($timestamp) > $hoursAgo24->timestamp)
            ->values()
            ->all();

        // Add new timestamp
        $viewedIps[$ipAddress][] = now()->toDateTimeString();

        // Check if this IP has exceeded max impressions
        if (count($viewedIps[$ipAddress]) <= $this->max_impressions_per_ip) {
            $this->increment('impressions');

            // Count unique IPs
            $uniqueCount = count(array_filter($viewedIps, fn ($ips) => count($ips) > 0));
            $this->update(['unique_ips' => $uniqueCount, 'viewed_ips' => $viewedIps]);
        } else {
            // Still update viewed_ips even if not counting impression
            $this->update(['viewed_ips' => $viewedIps]);
        }
    }

    /**
     * Check if IP can view this ad today
     */
    public function canShowToIp(string $ipAddress): bool
    {
        $viewedIps = $this->viewed_ips ?? [];

        if (! isset($viewedIps[$ipAddress])) {
            return true;
        }

        // Count views in last 24 hours
        $HoursAgo = now()->subHours(24);
        $recentViews = collect($viewedIps[$ipAddress])
            ->filter(fn ($timestamp) => strtotime($timestamp) > $HoursAgo->timestamp)
            ->count();

        return $recentViews < $this->max_impressions_per_ip;
    }
}
