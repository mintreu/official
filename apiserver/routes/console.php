<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Api\ApiKey;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(fn () => ApiKey::resetDailyCounters())->dailyAt('00:00');
Schedule::call(fn () => ApiKey::resetMonthlyCounters())->monthlyOn(1, '00:00');
