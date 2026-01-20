<?php

namespace Mintreu\LaravelTwist\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mintreu\LaravelTwist\LaravelTwist
 */
class LaravelTwist extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Mintreu\LaravelTwist\LaravelTwist::class;
    }
}
