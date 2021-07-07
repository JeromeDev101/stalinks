<?php

namespace App\Listeners;

use App\Events\SellerReceivesOrderEvent;
use App\Notifications\SellerReceivesOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SellerReceivesOrderListener
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
    public function handle(SellerReceivesOrderEvent $event)
    {
        $event->user->notify(new SellerReceivesOrder($event->backlink));
    }
}
