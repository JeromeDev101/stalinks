<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ValidationReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $unvalidated, $days;

    /**
     * Create a new message instance.
     *
     * @param $unvalidated
     * @param $days
     */
    public function __construct($unvalidated, $days)
    {
        $this->days = $days;
        $this->unvalidated = $unvalidated;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->days === 1) {
            return $this->view('validation.validation_reminder_1')->subject('Validation Reminder');
        } else if ($this->days === 2) {
            return $this->view('validation.validation_reminder_2')->subject('Validation Reminder');
        } else {
            return $this->view('validation.validation_reminder_3')->subject('Validation Reminder');
        }
    }
}
