<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('registration:remind')->daily();
        $schedule->command('validation:remind')->daily();
        $schedule->command('deposit:remind')->daily();
        $schedule->command('buyer-newsletter:send')->daily();
        $schedule->command('article-queue:remind')->daily();
        $schedule->command('exam-duration:notify')->daily();
        $schedule->command('expiring-tools-notification:send')->daily();
        $schedule->command('article:remind')->hourly();
        $schedule->command('backup:run')->daily();
        $schedule->command('backup:clean')->daily();
        $schedule->command('mail_logs_status:update')->daily();
        $schedule->command('system-logs:flush')->daily();
        $schedule->command('email_attachments:delete')->daily();
        $schedule->command('health:check')->everyFiveMinutes();
        $schedule->command('alexa:reset')->monthly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
