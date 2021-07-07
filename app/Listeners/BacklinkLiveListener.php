<?php

namespace App\Listeners;

use App\Events\BacklinkLiveEvent;
use App\Notifications\BacklinkLive;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BacklinkLiveListener
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
    public function handle(BacklinkLiveEvent $event)
    {
        $event->user->notify(new BacklinkLive($event->backlink));
    }
}
