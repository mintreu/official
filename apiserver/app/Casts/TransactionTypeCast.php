<?php

declare(strict_types=1);

namespace App\Casts;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TransactionTypeCast: string implements HasColor, HasIcon, HasLabel
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';
    case REFUND = 'refund';
    case CHARGEBACK = 'chargeback';
    case ADJUSTMENT = 'adjustment';
    case HOLD = 'hold';
    case RELEASE = 'release';
    case WITHDRAWAL = 'withdrawal';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::CREDIT => 'success',
            self::DEBIT => 'danger',
            self::REFUND => 'info',
            self::CHARGEBACK, self::HOLD, self::WITHDRAWAL => 'warning',
            self::ADJUSTMENT => 'gray',
            self::RELEASE => 'success',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::CREDIT => 'heroicon-o-arrow-down-circle',
            self::DEBIT => 'heroicon-o-arrow-up-circle',
            self::REFUND, self::CHARGEBACK => 'heroicon-o-arrow-uturn-left',
            self::ADJUSTMENT => 'heroicon-o-adjustments-horizontal',
            self::HOLD => 'heroicon-o-pause-circle',
            self::RELEASE => 'heroicon-o-play-circle',
            self::WITHDRAWAL => 'heroicon-o-currency-rupee',
        };
    }

    public function isPositive(): bool
    {
        return in_array($this, [self::CREDIT, self::REFUND, self::RELEASE], true);
    }
}
