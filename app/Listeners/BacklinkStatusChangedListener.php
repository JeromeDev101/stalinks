<?php

namespace App\Listeners;

use App\Events\BacklinkStatusChangedEvent;
use App\Notifications\BacklinkStatusChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BacklinkStatusChangedListener
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
    public function handle(BacklinkStatusChangedEvent $event)
    {
        $event->user->notify(new BacklinkStatusChanged($event->backlink));
    }
}
