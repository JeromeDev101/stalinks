<?php

namespace App\Listeners;

use App\Events\UserValidateEvent;
use App\Notifications\UserValidated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserValidateListener
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
     * @param  UserValidateEvent  $event
     * @return void
     */
    public function handle(UserValidateEvent $event)
    {
        $event->user->notify(new UserValidated($event->input, $event->user));
    }
}
