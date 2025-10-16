<?php

use App\Console\Commands\ProcessInstructorPayouts;
use App\Console\Commands\TestCMD;
use Illuminate\Support\Facades\Schedule;

if (app()->environment('local')) {
    // ✅ Local: Run TestCMD every minute for quick testing
    Schedule::command(TestCMD::class)->everyMinute();
    // Schedule::command(ProcessInstructorPayouts::class)->everyMinute();
}

if (app()->environment('production')) {
    // ✅ Production: Run the payout command on the 1st of each month at 00:01
    Schedule::command(ProcessInstructorPayouts::class)
        ->monthlyOn(1, '00:01');
    Schedule::command(TestCMD::class)->daily();
}
