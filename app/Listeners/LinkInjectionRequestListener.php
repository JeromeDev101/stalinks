<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\LinkInjection;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\LinkInjectionRequestEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class LinkInjectionRequestListener
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
    public function handle(LinkInjectionRequestEvent $event)
    {
        $event->user->notify(new LinkInjection($event->user, $event->injection, 'seller'));

        $team = User::whereIn('role_id', [8,1])->where('isOurs', 0)->where('status', 'active')->get();

        Notification::send($team, new LinkInjection($event->user, $event->injection, 'team'));
    }
}
