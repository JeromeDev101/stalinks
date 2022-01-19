<?php

namespace App\Console\Commands;

use App\Jobs\SendReminderEmail;
use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

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
     */
    public function handle()
    {
        // send email reminder for day 1

        $one_notifications = $this->getNotifications(1);

        $this->sendNotification($one_notifications, 1);

        if ($one_notifications->count()) {
            $this->updateNotifications($one_notifications->pluck('id')->toArray(), 1);
        }

        // send email reminder for day 2

        $two_notifications = $this->getNotifications(2);

        $this->sendNotification($two_notifications, 2);

        if ($two_notifications->count()) {
            $this->updateNotifications($two_notifications->pluck('id')->toArray(), 2);
        }

        // send email reminder for day 3

        $three_notifications = $this->getNotifications(3);

        $this->sendNotification($three_notifications, 3);

        if ($three_notifications->count()) {
            $this->updateNotifications($three_notifications->pluck('id')->toArray(), 3);
        }
    }

    /**
     * get users based on date
     *
     * @param $days
     * @return Collection
     */
    private function getNotifications ($days) {
        $end_days = [
            1 => 2,
            2 => 3,
        ];

        $notifications = Registration::whereNotNull('verification_code')->where(function($query) {
            $query->where('reminded_days', '!=', 8)->orWhereNull('reminded_days');
        });

        if ($days === 1) {
            $notifications = $notifications->whereNull('reminded_at');
        } else {
            $notifications = $notifications->whereNotNull('reminded_at');
        }

        if ($days === 3) {
            $notifications = $notifications->where('created_at', '<=', Carbon::now()->subDays($days));
        } else {
            $notifications = $notifications->where('created_at', '<=', Carbon::now()->subDays($days))
                ->where('created_at', '>=', Carbon::now()->subDays($end_days[$days]));
        }

        if ($days === 1) {
            $notifications = $notifications->whereNull('reminded_days');
        } else {
            $notifications = $notifications->where('reminded_days', '!=', $days);
        }

        $notifications = $notifications->get();

        return $notifications;
    }

    /**
     * send email reminder for users
     *
     * @param $notifications
     * @param $days
     */
    private function sendNotification ($notifications, $days) {

        if (!$notifications->count()) {
            $this->info('No Scheduled Notifications need to be sent for day ' . $days );

            return;
        }

        $this->info('Starting Sending Notifications for day ' . $days);

        foreach ($notifications as $notification) {
            try {
                SendReminderEmail::dispatch($notification, $days)->onQueue('emails');

                if (isset($notification->team_in_charge)) {
                    $qc = User::find($notification->team_in_charge);
                    SendReminderEmail::dispatch($qc, $days, $notification)->onQueue('emails');
                }

            } catch (\Exception $e) {
                \Log::error($e);
            }
        }

        $this->info('Finished Sending Notifications for day ' . $days);

    }

    /**
     * update users
     *
     * @param $ids
     * @param $days
     */
    private function updateNotifications ($ids, $days) {
        Registration::whereIn('id', $ids)->update([
            'reminded_at' => Carbon::now(),
            'reminded_days' => $days
        ]);
    }
}
