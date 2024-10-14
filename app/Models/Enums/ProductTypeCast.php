<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ProductTypeCast: string implements HasLabel, HasIcon, HasColor
{
    // API-based products, typically subscription-based
    case API = 'api';

    // Standard one-time purchase products
    case STANDARD = 'standard';

    /**
     * Get the label for the product type.
     *
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return match ($this) {
            self::API => 'API Product',
            self::STANDARD => 'Standard Product',
        };
    }

    /**
     * Get the color associated with the product type.
     *
     * @return string|array|null
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::API => 'warning',        // Color for API products
            self::STANDARD => 'success',   // Color for standard products
        };
    }

    /**
     * Get the icon associated with the product type.
     *
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::API => 'heroicon-s-globe-alt',        // Icon for API products
            self::STANDARD => 'heroicon-o-cube',    // Icon for standard products
        };
    }
}
