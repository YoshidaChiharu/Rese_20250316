<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// 当日予約分のリマインダーメール送信ジョブをキューに投入
Schedule::command('app:send-reminders')->dailyAt('10:00');
