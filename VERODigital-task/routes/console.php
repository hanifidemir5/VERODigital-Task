<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    \Log::info('Scheduled task started.');

    try {
        fetchAndSaveData();
        \Log::info('fetchAndSaveData called successfully.');
    } catch (\Exception $e) {
        \Log::error('Scheduled task failed: ' . $e->getMessage());
    }
})->everyMinute();