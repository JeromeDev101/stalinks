<?php

namespace App\Listeners;

use App\Events\SellerConfirmationEvent;
use App\Notifications\SellerConfirmation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SellerConfirmationListener
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
    public function handle($event)
    {
        $event->seller->notify(new SellerConfirmation($event->backlink, $event->seller));
    }
}
