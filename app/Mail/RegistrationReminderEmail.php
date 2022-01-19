<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $notification;
    public $days;

    /**
     * Create a new message instance.
     *
     * @param $token
     * @param $notification
     * @param $days
     */
    public function __construct($token, $notification, $days)
    {
        $this->token = $token;
        $this->notification = $notification;
        $this->days = $days;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->days === 1) {
            return $this->view('verification_reminder')->subject('Registration Reminder');
        } else if ($this->days === 2) {
            return $this->view('verification_reminder_2')->subject('Registration Reminder');
        } else {
            return $this->view('verification_reminder_3')->subject('Registration Reminder');
        }
    }
}
