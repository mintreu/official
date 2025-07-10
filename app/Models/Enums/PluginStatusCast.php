<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PluginStatusCast: string implements HasLabel, HasIcon, HasColor
{
    case MARKETPLACE = 'marketplace';
    case INSTALLED = 'installed';
    case DOWNLOADED = 'downloaded';
    case ACTIVE = 'active';

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::MARKETPLACE => 'gray',
            self::DOWNLOADED    => 'warning',
            self::INSTALLED     => 'info',
            self::ACTIVE        => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::MARKETPLACE => 'heroicon-s-cloud-arrow-down',
            self::DOWNLOADED    => 'heroicon-o-archive-box',
            self::INSTALLED     => 'heroicon-o-check-circle',
            self::ACTIVE        => 'heroicon-o-bolt',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MARKETPLACE => 'Download Now',
            self::DOWNLOADED    => 'Downloaded',
            self::INSTALLED     => 'Installed',
            self::ACTIVE        => 'Active',
        };
    }
}
