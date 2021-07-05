<?php

namespace App\Listeners;

use App\Events\BuyerDebitedEvent;
use App\Notifications\BuyerDebitted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BuyerDebittedListener
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
    public function handle(BuyerDebitedEvent $event)
    {
        $event->backlink->user->notify(new BuyerDebitted($event->totalAmount, $event->backlinkIds));
    }
}
