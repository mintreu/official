<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Product types determine frontend page behavior and download/access flow
 *
 * Frontend routes: /products/{slug}
 * Each type renders differently in the Vue product detail page
 */
enum ProductType: string implements HasColor, HasDescription, HasIcon, HasLabel
{
    /**
     * Downloadable software/templates/assets
     * - Shows download button
     * - Source URL masked via secure token
     * - Supports multiple sources (GitHub, BitBucket, GDrive, etc.)
     */
    case Downloadable = 'downloadable';

    /**
     * Your hosted API services (this project handles subscriptions)
     * - Shows pricing plans
     * - API documentation link
     * - Generates/displays API credentials after purchase
     */
    case ApiService = 'api_service';

    /**
     * Third-party APIs you resell (like OpenRouter/RapidAPI model)
     * - Subscription handled internally (payment → verify → store credentials)
     * - Shows 3rd party interface in iframe/container (closable preview)
     * - Displays API credentials after purchase
     * - Shows rate limits/usage stats from external API
     */
    case ApiReferral = 'api_referral';

    /**
     * Free/open-source products
     * - Direct download without auth
     * - GitHub repo link visible (for backlinks)
     * - No license restrictions
     */
    case Freebie = 'freebie';

    /**
     * Demo/trial versions
     * - Limited access (30 days or feature-limited)
     * - Upgrade CTA shown
     */
    case Demo = 'demo';

    public function getLabel(): string
    {
        return match ($this) {
            self::Downloadable => 'Downloadable',
            self::ApiService => 'API Service',
            self::ApiReferral => 'API Referral',
            self::Freebie => 'Freebie',
            self::Demo => 'Demo',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Downloadable => 'Software, templates, assets with secure download',
            self::ApiService => 'Your hosted API with subscription plans',
            self::ApiReferral => 'Third-party API you resell',
            self::Freebie => 'Free open-source with visible GitHub link',
            self::Demo => 'Trial version with limited access',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Downloadable => 'primary',
            self::ApiService => 'success',
            self::ApiReferral => 'info',
            self::Freebie => 'gray',
            self::Demo => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Downloadable => 'heroicon-o-arrow-down-tray',
            self::ApiService => 'heroicon-o-server-stack',
            self::ApiReferral => 'heroicon-o-link',
            self::Freebie => 'heroicon-o-gift',
            self::Demo => 'heroicon-o-play-circle',
        };
    }

    /**
     * Whether product requires payment
     */
    public function isPaid(): bool
    {
        return in_array($this, [
            self::Downloadable,
            self::ApiService,
            self::ApiReferral,
        ]);
    }

    /**
     * Whether to show public source URL (GitHub link for backlinks)
     */
    public function showsPublicSource(): bool
    {
        return $this === self::Freebie;
    }

    /**
     * Whether product has subscription plans
     */
    public function hasPlans(): bool
    {
        return in_array($this, [
            self::ApiService,
            self::ApiReferral,
        ]);
    }

    /**
     * Whether downloads use secure masked URLs
     */
    public function usesSecureDownload(): bool
    {
        return in_array($this, [
            self::Downloadable,
            self::Demo,
        ]);
    }

    /**
     * Frontend component name for Vue router
     */
    public function frontendComponent(): string
    {
        return match ($this) {
            self::Downloadable => 'ProductDownloadable',
            self::ApiService => 'ProductApiService',
            self::ApiReferral => 'ProductApiReferral',
            self::Freebie => 'ProductFreebie',
            self::Demo => 'ProductDemo',
        };
    }
}
