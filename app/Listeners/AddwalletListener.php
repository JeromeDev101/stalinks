<?php

namespace App\Listeners;

use App\Events\AddWalletEvent;
use App\Models\User;
use App\Notifications\AddWalletCredit;
use App\Notifications\AddWalletCreditQcAdmin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

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

        // notify qc managers and admin

        $qc_managers_and_admins = User::where(function($query) {
                $query->whereIn('role_id', [8, 1])
                    ->orWhere('type', 10);
            })->whereNull('deleted_at')
            ->where('status', 'active')
            ->get();

        Notification::send($qc_managers_and_admins, new AddWalletCreditQcAdmin($event->user, $event->amount));
    }
}
