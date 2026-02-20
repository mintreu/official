<?php

namespace App\Services;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money as MoneyPHP;
use NumberFormatter;

final class MoneyService
{
    private readonly MoneyPHP $money;

    private Currency $currency;

    public function __construct(MoneyPHP|string|int $value = 0)
    {
        $this->currency = new Currency('INR');

        if ($value instanceof MoneyPHP) {
            $this->money = $value;
        } else {
            $amount = is_string($value) ? (int) $value : $value;
            $this->money = new MoneyPHP($amount, $this->currency);
        }
    }

    public static function make(MoneyPHP|string|int $value = 0): self
    {
        return new self($value);
    }

    public function toPaise(float|int $rupees): int
    {
        return (int) round($rupees * 100);
    }

    public function fromPaise(int $paise): MoneyPHP
    {
        return new MoneyPHP($paise, $this->currency);
    }

    public function fromRupees(string|float|int $rupees): MoneyPHP
    {
        $paise = $this->toPaise((float) $rupees);

        return new MoneyPHP($paise, $this->currency);
    }

    public function formatMoney(MoneyPHP $money): string
    {
        static $formatter = null;

        if ($formatter === null) {
            $numberFormatter = new NumberFormatter('en_IN', NumberFormatter::CURRENCY);
            $formatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies);
        }

        return $formatter->format($money);
    }

    public function formatPaise(int $paise): string
    {
        return $this->formatMoney(new MoneyPHP($paise, $this->currency));
    }

    public static function format(int|string|null $paise): string
    {
        if ($paise === null || $paise === '') {
            return 'INR 0.00';
        }

        return (new self((int) $paise))->formatted();
    }

    public function zero(): MoneyPHP
    {
        return new MoneyPHP(0, $this->currency);
    }

    public function formatForApi(int $paise): array
    {
        $money = new MoneyPHP($paise, $this->currency);
        $negative = $paise < 0;
        $absPaise = abs($paise);
        $rupeesInt = intdiv($absPaise, 100);
        $remainder = $absPaise % 100;
        $rupeesString = ($negative ? '-' : '').$rupeesInt.'.'.str_pad((string) $remainder, 2, '0', STR_PAD_LEFT);

        return [
            'paise' => $paise,
            'rupees' => $rupeesString,
            'formatted' => $this->formatMoney($money),
            'display_value' => $rupeesString,
        ];
    }

    public function add(self|int $other): self
    {
        $otherMoney = $other instanceof self
            ? $other->money
            : new MoneyPHP($other, $this->money->getCurrency());

        return new self($this->money->add($otherMoney));
    }

    public function subtract(self|int $other): self
    {
        $otherMoney = $other instanceof self
            ? $other->money
            : new MoneyPHP($other, $this->money->getCurrency());

        return new self($this->money->subtract($otherMoney));
    }

    public function multiply(int|float $multiplier): self
    {
        return new self($this->money->multiply((string) $multiplier));
    }

    public function divide(int|float $divisor): self
    {
        return new self($this->money->divide((string) $divisor));
    }

    public function getAmount(): int
    {
        return (int) $this->money->getAmount();
    }

    public function getCurrency(): Currency
    {
        return $this->money->getCurrency();
    }

    public function getCurrencyCode(): string
    {
        return $this->money->getCurrency()->getCode();
    }

    public function sameAs(self|string|int $value): bool
    {
        return $value instanceof self
            ? $this->money->equals($value->money)
            : $this->money->equals((new self($value))->money);
    }

    public function formatted(): string
    {
        static $formatter = null;

        if ($formatter === null) {
            $numberFormatter = new NumberFormatter('en_IN', NumberFormatter::CURRENCY);
            $formatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies);
        }

        return $formatter->format($this->money);
    }

    public static function formatStatic(int|string|null $value): string
    {
        if ($value === null) {
            return 'INR 0.00';
        }

        return (new self($value))->formatted();
    }

    public static function toRupees(int|string|null $paise): float
    {
        if ($paise === null || $paise === '') {
            return 0.0;
        }

        return (int) $paise / 100;
    }

    public static function toRupeesString(int|string|null $paise): string
    {
        if ($paise === null || $paise === '') {
            return '0.00';
        }

        $amount = (int) $paise;
        $negative = $amount < 0;
        $absAmount = abs($amount);
        $rupees = intdiv($absAmount, 100);
        $remainder = $absAmount % 100;

        return ($negative ? '-' : '').$rupees.'.'.str_pad((string) $remainder, 2, '0', STR_PAD_LEFT);
    }
}
