<?php

namespace App\Models\Enums\Product;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ProductSupportLevelCast: string implements HasLabel, HasIcon, HasColor
{
    case BASIC = 'basic';
    case STANDARD = 'standard';
    case PREMIUM = 'premium';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::BASIC => 'gray',
            self::STANDARD => 'blue',
            self::PREMIUM => 'gold',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::BASIC => 'heroicon-o-support', // Example Heroicon for basic
            self::STANDARD => 'heroicon-o-badge-check', // Example Heroicon for standard
            self::PREMIUM => 'heroicon-o-star', // Example Heroicon for premium
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BASIC => 'Basic Support',
            self::STANDARD => 'Standard Support',
            self::PREMIUM => 'Premium Support',
        };
    }
}
