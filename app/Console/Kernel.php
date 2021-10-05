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
        $schedule->command('registration:remind')->everyMinute();
        $schedule->command('backup:run')->daily();
        $schedule->command('backup:clean')->daily();
        $schedule->command('mail_logs_status:update')->daily();
        $schedule->command('system-logs:flush')->daily();
        $schedule->command('email_attachments:delete')->daily();
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
