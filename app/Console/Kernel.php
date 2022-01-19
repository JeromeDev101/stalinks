<?php

namespace App\Console;

use App\Jobs\RegistrationRemind;
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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('registration:remind')->twiceDaily(12, 24);
        $schedule->command('validation:remind')->twiceDaily(12, 24);
        $schedule->command('backup:run')->daily();
        $schedule->command('backup:clean')->daily();
        $schedule->command('mail_logs_status:update')->daily();
        $schedule->command('system-logs:flush')->daily();
        $schedule->command('email_attachments:delete')->daily();
        $schedule->command('health:check')->everyFiveMinutes();
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
