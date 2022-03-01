<?php

namespace App\Listeners;

use App\Events\BillingReuploadDocEvent;
use App\Notifications\BillingReuploadDoc;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BillingReuploadDocListener
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
     * @param  BillingReuploadDocEvent  $event
     * @return void
     */
    public function handle(BillingReuploadDocEvent $event)
    {
        $event->billing->user->notify(new BillingReuploadDoc($event));
    }
}
