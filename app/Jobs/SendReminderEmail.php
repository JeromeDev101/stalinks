<?php

namespace App\Jobs;

use App\Mail\RegistrationReminderEmail;
use App\Mail\SendReminderToLeadEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notification;

    protected $subAccount;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification, $subAccount = null)
    {
        $this->notification = $notification;
        $this->subAccount = $subAccount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->subAccount) {
            Mail::to($this->notification->email)->send(new SendReminderToLeadEmail($this->subAccount->username));
        }

        Mail::to($this->notification->email)->send(new RegistrationReminderEmail($this->notification->verification_code));
    }
}
