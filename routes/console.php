<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Runs every weekday at 23:59 — marks employees who never clocked in as absent
Schedule::command('attendance:mark-absent')->weekdays()->dailyAt('23:59');
