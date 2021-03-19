<?php

namespace App\Repositories\Traits;

use App\Events\BestPriceGenerationEnd;
use App\Events\BestPriceGenerationStart;
use App\Events\Buyer\BuyEvent;
use App\Events\GenerateBestPricesDone;
use App\Events\SellerReceivesOrderEvent;
use App\Models\Notif;

trait NotificationTrait
{
    public function buyEventNotification($user, $data) : void
    {
        Notif::create([
            'user_id' => $user,
            'notification' => 'You have purchased from '. $data->url_advertiser .' on '. date('Y-m-d') . ' at $'. $data->price . ' follow up with Backlink ID '. $data->id,
        ]);

        broadcast(new BuyEvent($user));
    }

    public function sellerReceivesOrderNotification($userId, $data) : void
    {
        Notif::create([
            'user_id' => $userId,
            'notification' => 'You have received an order for ' . $data->url_advertiser . ' on ' . date('Y-m-d') . ' follow up with Backlink ID ' . $data->id,
        ]);

        broadcast(new SellerReceivesOrderEvent($userId));
    }

    public function generateBestPricesDoneNotification($userId)
    {
        broadcast(new GenerateBestPricesDone($userId));
    }

    public function generateBestPriceStart()
    {
        broadcast(new BestPriceGenerationStart());
    }

    public function generateBestPriceEnd()
    {
        broadcast(new BestPriceGenerationEnd());

    }
}
