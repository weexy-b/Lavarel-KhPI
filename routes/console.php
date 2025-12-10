<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Щоденна перевірка прострочених задач
Schedule::command('app:check-overdue-tasks')
    ->dailyAt('08:00')
    ->onSuccess(function () {
        Log::info('Scheduler: CheckOverdueTasks finished successfully.');
    })
    ->onFailure(function () {
        Log::error('Scheduler: CheckOverdueTasks failed.');
    });

// Щотижневий звіт (Понеділок о 9:00)
Schedule::command('app:generate-report')
    ->weeklyOn(1, '09:00') // 1 = Понеділок
    ->appendOutputTo(storage_path('logs/scheduler.log')); // Логування виводу команди в окремий файл
