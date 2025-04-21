<?php

use App\Console\Commands\ProcessInstructorPayouts;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Per minute
Schedule::command(ProcessInstructorPayouts::class)->everyMinute();
// Schedule::command(ProcessInstructorPayouts::class)->monthlyOn(1, '00:01');
