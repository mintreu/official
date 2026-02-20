<?php

declare(strict_types=1);

namespace App\Casts;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TransactionStatusCast: string implements HasColor, HasIcon, HasLabel
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case REVERSED = 'reversed';
    case ON_HOLD = 'on_hold';
    case EXPIRED = 'expired';

    public function getLabel(): string
    {
        return str_replace('_', ' ', ucfirst($this->value));
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING, self::ON_HOLD => 'warning',
            self::PROCESSING, self::REFUNDED => 'info',
            self::COMPLETED => 'success',
            self::FAILED => 'danger',
            self::CANCELLED, self::EXPIRED => 'gray',
            self::REVERSED => 'warning',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::PENDING, self::EXPIRED => 'heroicon-o-clock',
            self::PROCESSING => 'heroicon-o-arrow-path',
            self::COMPLETED => 'heroicon-o-check-circle',
            self::FAILED => 'heroicon-o-x-circle',
            self::CANCELLED => 'heroicon-o-minus-circle',
            self::REFUNDED, self::REVERSED => 'heroicon-o-arrow-uturn-left',
            self::ON_HOLD => 'heroicon-o-pause-circle',
        };
    }

    public function canBeRefunded(): bool
    {
        return $this === self::COMPLETED;
    }
}
