<?php

declare(strict_types=1);

namespace App\Casts;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PaymentMethodCast: string implements HasColor, HasIcon, HasLabel
{
    case CASH = 'cash';
    case COD = 'cod';
    case WALLET = 'wallet';
    case COINS = 'coins';
    case BANK_TRANSFER = 'bank_transfer';
    case CASHFREE = 'cashfree';
    case RAZORPAY = 'razorpay';
    case STRIPE = 'stripe';
    case PAYTM = 'paytm';
    case UPI = 'upi';

    public function getLabel(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::COD => 'Cash on Delivery',
            self::WALLET => 'Wallet',
            self::COINS => 'Coins',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::CASHFREE => 'Cashfree',
            self::RAZORPAY => 'Razorpay',
            self::STRIPE => 'Stripe',
            self::PAYTM => 'Paytm',
            self::UPI => 'UPI',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::CASH, self::COD => 'success',
            self::WALLET => 'primary',
            self::COINS => 'emerald',
            self::BANK_TRANSFER => 'info',
            self::CASHFREE => 'purple',
            self::RAZORPAY => 'blue',
            self::STRIPE => 'indigo',
            self::PAYTM => 'cyan',
            self::UPI => 'green',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::CASH, self::COD => 'heroicon-o-banknotes',
            self::WALLET => 'heroicon-o-wallet',
            self::COINS => 'heroicon-o-currency-rupee',
            self::BANK_TRANSFER => 'heroicon-o-building-library',
            self::CASHFREE, self::RAZORPAY, self::STRIPE, self::PAYTM => 'heroicon-o-credit-card',
            self::UPI => 'heroicon-o-qr-code',
        };
    }

    public function isNative(): bool
    {
        return in_array($this, [
            self::CASH,
            self::COD,
            self::WALLET,
            self::COINS,
            self::BANK_TRANSFER,
        ], true);
    }
}
