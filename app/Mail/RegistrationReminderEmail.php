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

    /**
     * Create a new message instance.
     *
     * @param $token
     * @param $name
     */
    public function __construct($token, $notification)
    {
        $this->token = $token;
        $this->notification = $notification;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('verification_reminder')->subject('Registration Reminder');
    }
}
