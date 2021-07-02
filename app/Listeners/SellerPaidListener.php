<?php

namespace App\Listeners;

use App\Events\SellerPaidEvent;
use App\Notifications\SellerPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SellerPaidListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(SellerPaidEvent $event)
    {
        $event->user->notify(new SellerPaid($event));
    }
}
