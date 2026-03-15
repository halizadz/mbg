<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-cleanup data transaksi lama (> 6 bulan) setiap hari jam 02:00
Schedule::command('app:cleanup-old-data')->dailyAt('02:00');
