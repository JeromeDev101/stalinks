<?php

namespace App\Listeners;

use App\Events\AddWalletEvent;
use App\Notifications\AddWalletCredit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddwalletListener
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
    public function handle(AddWalletEvent $event)
    {
        $event->user->notify(new AddWalletCredit($event->amount));
    }
}
