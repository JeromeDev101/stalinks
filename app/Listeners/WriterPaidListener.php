<?php

namespace App\Listeners;

use App\Events\WriterPaidEvent;
use App\Notifications\WriterPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WriterPaidListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param WriterPaidEvent $event
     * @return void
     */
    public function handle(WriterPaidEvent $event)
    {
        $event->user->notify(new WriterPaid($event));
    }
}
