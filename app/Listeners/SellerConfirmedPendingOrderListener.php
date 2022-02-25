<?php

namespace App\Listeners;

use App\Events\SellerConfirmedPendingOrderEvent;
use App\Models\User;
use App\Notifications\SellerConfirmedPendingOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SellerConfirmedPendingOrderListener
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
     * @param  SellerConfirmedPendingOrderEvent  $event
     * @return void
     */
    public function handle(SellerConfirmedPendingOrderEvent $event)
    {
        $team_in_charge = $event->backlink->publisher->user->registration->in_charge;

        if ($team_in_charge) {
            $team_in_charge->notify(new SellerConfirmedPendingOrder($event->backlink, $event->confirmation));
        } else {
            $cs = User::where('role_id', 6)->where('status', 'active')->where('isOurs', 0)->get();

            Notification::send($cs, new SellerConfirmedPendingOrder($event->backlink, $event->confirmation));
        }
    }
}