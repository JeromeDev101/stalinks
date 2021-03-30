<?php

namespace App\Console\Commands;

use App\Jobs\SendReminderEmail;
use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendRegistrationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registration:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder to unverified users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notifications = Registration::whereNotNull('verification_code')
            ->whereNull('reminded_at')
            ->where('created_at', '<=', Carbon::now()->addDay())->get();

        if (!$notifications->count()) {
            $this->info('No Scheduled Notifications need to be sent.');

            return;
        }

        $this->info('Starting Sending Notifications');

        foreach ($notifications as $notification) {
            try {
                SendReminderEmail::dispatch($notification)->onQueue('emails');

                if (isset($notification->team_in_charge)) {
                    $qc = User::find($notification->team_in_charge);
                    SendReminderEmail::dispatch($qc, $notification)->onQueue('emails');
                }

                $notification->update([
                    'reminded_at' => Carbon::now()
                ]);
            } catch (\Exception $e) {
                \Log::error($e);
            }
        }

        $this->info('Finished Sending Notifications');
    }
}
