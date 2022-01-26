<?php

namespace App\Listeners;

use App\Events\UserUnvalidatedEvent;
use App\Notifications\UserUnvalidated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserUnvalidatedListener
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
     * @param  UserUnvalidatedEvent  $event
     * @return void
     */
    public function handle(UserUnvalidatedEvent $event)
    {
        $event->user->notify(new UserUnvalidated($event->input, $event->user));
    }
}
