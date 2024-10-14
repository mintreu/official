<?php

namespace App\Services\MoneyService\Contracts;

use App\Services\MoneyService\Money;

interface MoneyInterface
{

    public function addOnce(Money|int|float$addend): self;
    public function add(Money|int|float $addend): self;
    public function subOnce(Money|int|float$subtrahend):self;
    public function subtract(Money|int|float$subtrahend):self;
    public function multiplyOnce(int|float $multiplier): self;
    public function multiply(int|float $multiplier):self;
    public function divideOnce(int|float $divisor): self;
    public function divide(int|float $divisor):self;


}
