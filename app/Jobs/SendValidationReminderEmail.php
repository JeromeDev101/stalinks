<?php

namespace App\Jobs;

use App\Mail\ValidationReminderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendValidationReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $unvalidated, $days;

    /**
     * Create a new job instance.
     *
     * @param $unvalidated
     * @param $days
     */
    public function __construct($unvalidated, $days)
    {
        $this->unvalidated = $unvalidated;
        $this->days = $days;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->unvalidated->email)->send(new ValidationReminderEmail($this->unvalidated, $this->days));
    }
}
