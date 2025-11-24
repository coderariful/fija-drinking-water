<?php

namespace App\Console;

use App\Helpers\CronSMS;
use App\Helpers\SMS;
use App\Models\Customer;
use App\Models\SmsCron;
use App\Models\SmsHistory;
use App\Models\SmsTemplate;
use DB;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->call([CronSMS::class, 'dailySMS']);//->dailyAt("12:00");

        $schedule->call([CronSMS::class, 'monthlySMS'])->lastDayOfMonth("12:00");
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
