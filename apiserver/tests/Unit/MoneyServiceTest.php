<?php

use App\Services\MoneyService;
use Money\Currency;
use Money\Money;

beforeEach(function () {
    $this->moneyService = app(MoneyService::class);
});

test('initializes with INR currency', function () {
    $currency = $this->moneyService->getCurrency();

    expect($currency)->toBeInstanceOf(Currency::class);
    expect($currency->getCode())->toBe('INR');
});

test('converts rupees to paise correctly', function () {
    expect($this->moneyService->toPaise(100.00))->toBe(10000);
    expect($this->moneyService->toPaise(125.50))->toBe(12550);
    expect($this->moneyService->toPaise(0.99))->toBe(99);
    expect($this->moneyService->toPaise(0.01))->toBe(1);
    expect($this->moneyService->toPaise(0))->toBe(0);
});

test('handles float precision edge cases', function () {
    expect($this->moneyService->toPaise(99.99))->toBe(9999);
    expect($this->moneyService->toPaise(99.9999))->toBe(10000);
    expect($this->moneyService->toPaise(99.999))->toBe(10000);
    expect($this->moneyService->toPaise(99.994))->toBe(9999);
});

test('creates Money objects correctly', function () {
    $money = $this->moneyService->fromPaise(15000);

    expect($money)->toBeInstanceOf(Money::class);
    expect($money->getAmount())->toBe('15000');
    expect($money->getCurrency()->getCode())->toBe('INR');
});

test('formats money as INR currency', function () {
    $money = $this->moneyService->fromPaise(15000);
    $formatted = $this->moneyService->formatMoney($money);

    expect($formatted)->toContain('150.00');
});

test('formats paise directly', function () {
    expect($this->moneyService->formatPaise(15000))->toContain('150.00');
    expect($this->moneyService->formatPaise(99))->toContain('0.99');
    expect($this->moneyService->formatPaise(1))->toContain('0.01');
    expect($this->moneyService->formatPaise(0))->toContain('0.00');
});

test('formats negative amounts', function () {
    expect($this->moneyService->formatPaise(-15000))->toContain('-');
    expect($this->moneyService->formatPaise(-15000))->toContain('150.00');
    expect($this->moneyService->formatPaise(-1))->toContain('-');
    expect($this->moneyService->formatPaise(-1))->toContain('0.01');
});

test('formats for API responses', function () {
    $apiResponse = $this->moneyService->formatForApi(15000);

    expect($apiResponse['paise'])->toBe(15000);
    expect($apiResponse['rupees'])->toBe('150.00');
    expect($apiResponse['formatted'])->toContain('150.00');
    expect($apiResponse['display_value'])->toBe('150.00');
});

test('static format helpers work', function () {
    expect(MoneyService::format(15000))->toContain('150.00');
    expect(MoneyService::format(99))->toContain('0.99');
    expect(MoneyService::format(1))->toContain('0.01');
    expect(MoneyService::format(0))->toContain('0.00');
    expect(MoneyService::format(null))->toBe('INR 0.00');

    expect(MoneyService::toRupees(15000))->toBe(150.00);
    expect(MoneyService::toRupees(1))->toBe(0.01);
    expect(MoneyService::toRupees(0))->toBe(0.0);
    expect(MoneyService::toRupees(null))->toBe(0.0);

    expect(MoneyService::toRupeesString(15000))->toBe('150.00');
    expect(MoneyService::toRupeesString(15050))->toBe('150.50');
    expect(MoneyService::toRupeesString(-99))->toBe('-0.99');
    expect(MoneyService::toRupeesString(null))->toBe('0.00');
});

test('immutable operations work', function () {
    $money = MoneyService::make(10000);
    $result = $money->add(5000)->subtract(2000)->multiply(2)->divide(2);

    expect($result->getAmount())->toBe(13000);
    expect($money->getAmount())->toBe(10000);
    expect($money->sameAs(10000))->toBeTrue();
    expect($money->formatted())->toContain('100.00');
});
