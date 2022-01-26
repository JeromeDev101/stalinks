<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DepositReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $no_transactions, $days;

    /**
     * Create a new message instance.
     *
     * @param $no_transactions
     * @param $days
     */
    public function __construct($no_transactions, $days)
    {
        $this->days = $days;
        $this->no_transactions = $no_transactions;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->days === 1) {
            return $this->view('buyer.deposit_reminder_1')->subject('Buyer Credit Reminder');
        } else if ($this->days === 2) {
            return $this->view('buyer.deposit_reminder_2')->subject('Buyer Credit Reminder');
        } else if ($this->days === 3) {
            return $this->view('buyer.deposit_reminder_3')->subject('Stalinks Survey A');
        }else {
            return $this->view('buyer.deposit_reminder_4')->subject('Stalinks Survey B');
        }
    }
}
