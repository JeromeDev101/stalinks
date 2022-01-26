<?php

namespace App\Jobs;

use App\Mail\DepositReminderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendDepositReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $no_transactions, $days;

    /**
     * Create a new job instance.
     *
     * @param $no_transactions
     * @param $days
     */
    public function __construct($no_transactions, $days)
    {
        $this->no_transactions = $no_transactions;
        $this->days = $days;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->no_transactions->email)->send(new DepositReminderEmail($this->no_transactions, $this->days));
    }
}
