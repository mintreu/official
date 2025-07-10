<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PluginTypeCast: string implements HasLabel, HasIcon, HasColor
{
    case STANDALONE = 'standalone';
    case BATCH = 'batch';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::STANDALONE => 'Standalone',
            self::BATCH => 'Batch Processor',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::STANDALONE => 'heroicon-o-cube',
            self::BATCH => 'heroicon-o-cog',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::STANDALONE => 'info',
            self::BATCH => 'warning',
        };
    }
}
