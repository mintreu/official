<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * License types for products
 * Controls usage limits, attribution requirements, and commercial rights
 */
enum LicenseType: string implements HasColor, HasDescription, HasIcon, HasLabel
{
    // FREE LICENSES
    case FreeSingleUse = 'free_single_use';
    case FreeAttribution = 'free_attribution';
    case FreeUnlimited = 'free_unlimited';

    // COMMERCIAL LICENSES
    case CommercialSingle = 'commercial_single';
    case CommercialTeam = 'commercial_team';
    case CommercialEnterprise = 'commercial_enterprise';

    // API & SUBSCRIPTION
    case ApiSubscription = 'api_subscription';
    case Demo = 'demo';

    public function getLabel(): string
    {
        return match ($this) {
            self::FreeSingleUse => 'Free - Single Use',
            self::FreeAttribution => 'Free - Attribution',
            self::FreeUnlimited => 'Free - Unlimited',
            self::CommercialSingle => 'Commercial - Single',
            self::CommercialTeam => 'Commercial - Team',
            self::CommercialEnterprise => 'Commercial - Enterprise',
            self::ApiSubscription => 'API Subscription',
            self::Demo => 'Demo',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::FreeSingleUse => 'One-time use, non-commercial projects only',
            self::FreeAttribution => 'Unlimited use with Mintreu attribution required',
            self::FreeUnlimited => 'Unlimited non-commercial use',
            self::CommercialSingle => 'Single commercial project',
            self::CommercialTeam => 'Up to 5 projects, same team',
            self::CommercialEnterprise => 'Unlimited projects, organization-wide',
            self::ApiSubscription => 'API access with rate limits per plan',
            self::Demo => '30-day trial access',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::FreeSingleUse, self::FreeAttribution, self::FreeUnlimited => 'gray',
            self::CommercialSingle => 'info',
            self::CommercialTeam => 'success',
            self::CommercialEnterprise => 'primary',
            self::ApiSubscription => 'warning',
            self::Demo => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::FreeSingleUse => 'heroicon-o-gift',
            self::FreeAttribution => 'heroicon-o-heart',
            self::FreeUnlimited => 'heroicon-o-sparkles',
            self::CommercialSingle => 'heroicon-o-user',
            self::CommercialTeam => 'heroicon-o-user-group',
            self::CommercialEnterprise => 'heroicon-o-building-office',
            self::ApiSubscription => 'heroicon-o-key',
            self::Demo => 'heroicon-o-play',
        };
    }

    public function isFree(): bool
    {
        return in_array($this, [
            self::FreeSingleUse,
            self::FreeAttribution,
            self::FreeUnlimited,
            self::Demo,
        ]);
    }

    public function isCommercial(): bool
    {
        return in_array($this, [
            self::CommercialSingle,
            self::CommercialTeam,
            self::CommercialEnterprise,
        ]);
    }

    public function maxUsages(): ?int
    {
        return match ($this) {
            self::FreeSingleUse => 1,
            self::CommercialSingle => 1,
            self::CommercialTeam => 5,
            default => null, // unlimited
        };
    }

    public function expiresInDays(): ?int
    {
        return match ($this) {
            self::Demo => 30,
            default => null,
        };
    }

    public function requiresAttribution(): bool
    {
        return $this === self::FreeAttribution;
    }
}
