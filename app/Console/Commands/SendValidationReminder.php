<?php

namespace App\Console\Commands;

use App\Jobs\SendValidationReminderEmail;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class SendValidationReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validation:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send validation reminder to unvalidated users';

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
        // send validation reminder for day 1
        $validation_reminder_one = $this->getUnvalidatedUsersInRegistration(1);

        $this->sendValidationReminderEmailToUnvalidatedUsers($validation_reminder_one, 1);

        if ($validation_reminder_one->count()) {
            $this->updateUnvalidatedUsers($validation_reminder_one->pluck('id')->toArray(), 1);
        }

        // send validation reminder for day 2
        $validation_reminder_two = $this->getUnvalidatedUsersInRegistration(2);

        $this->sendValidationReminderEmailToUnvalidatedUsers($validation_reminder_two, 2);

        if ($validation_reminder_two->count()) {
            $this->updateUnvalidatedUsers($validation_reminder_two->pluck('id')->toArray(), 2);
        }


        // send validation reminder for day 3
        $validation_reminder_three = $this->getUnvalidatedUsersInRegistration(3);

        $this->sendValidationReminderEmailToUnvalidatedUsers($validation_reminder_three, 3);

        if ($validation_reminder_three->count()) {
            $this->updateUnvalidatedUsers($validation_reminder_three->pluck('id')->toArray(), 3);
        }

    }

    /**
     * get unvalidated users based on validation email date
     *
     * @param $days
     * @return Collection
     */
    private function getUnvalidatedUsersInRegistration ($days) {
        $end_days = [
            1 => 2,
            2 => 3,
        ];

        $unvalidated = Registration::whereNull('verification_code')->where('email_via', 'validation_email');

        if ($days === 3) {
            $unvalidated = $unvalidated->where('validation_reminded_at', '<=', Carbon::now()->subDays($days));
        } else {
            $unvalidated = $unvalidated->where('validation_reminded_at', '<=', Carbon::now()->subDays($days))
                ->where('validation_reminded_at', '>=', Carbon::now()->subDays($end_days[$days]));
        }

        $unvalidated = $unvalidated->where(function ($query) use ($days) {
            $query->where('reminded_days', '!=', $days . '0')
                ->orWhereNull('reminded_days');
        });

        $unvalidated = $unvalidated->get();

        return $unvalidated;
    }

    /**
     * send email reminder for users
     *
     * @param $unvalidated
     * @param $days
     */
    private function sendValidationReminderEmailToUnvalidatedUsers ($unvalidated, $days) {

        if (!$unvalidated->count()) {
            $this->info('No validation reminder email need to be sent for day ' . $days );

            return;
        }

        $this->info('Starting sending validation reminder email for day ' . $days);

        foreach ($unvalidated as $un) {
            try {
                SendValidationReminderEmail::dispatch($un, $days)->onQueue('emails');
            } catch (\Exception $e) {
                \Log::error($e);
            }
        }

        $this->info('Finished sending validation reminder email for day ' . $days);

    }

    /**
     * update users
     *
     * @param $ids
     * @param $days
     */
    private function updateUnvalidatedUsers ($ids, $days) {
        Registration::whereIn('id', $ids)->update([
            'reminded_days' => $days . '0'
        ]);
    }
}
