<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\TeamInChargeUpdated;
use App\Events\TeamInChargeUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TeamInChargeUpdatedListener
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
     * @param  TeamInChargeUpdatedEvent  $event
     * @return void
     */
    public function handle(TeamInChargeUpdatedEvent $event)
    {
        $team_in_charge_user = User::find($event->team_in_charge);

        if ($team_in_charge_user) {
            $team_in_charge_user->notify(new TeamInChargeUpdated($team_in_charge_user, $event->user));
        }
    }
}
