<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 退会済みユーザーの匿名化（毎日深夜2時）
Schedule::command('users:anonymize-deleted')->dailyAt('02:00');

// 応募締切3日前リマインド（毎日朝9時）
Schedule::command('jobs:send-deadline-reminders')->dailyAt('09:00');
