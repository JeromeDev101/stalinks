<?php

namespace App\Repositories\Traits;

use App\Models\BuyerPurchased;
use Illuminate\Support\Facades\Auth;

trait BuyTrait
{
    public function updateStatus($id, $status, $id_publisher, $userId = null) : void {
        $user = Auth::user();

        BuyerPurchased::updateOrCreate([
            'publisher_id' => $id,
            'user_id_buyer' => $user ? $user->id : $userId
        ],[
            'user_id_buyer' => $user ? $user->id : $userId,
            'publisher_id' => $id_publisher,
            'status' => $status,
        ]);
    }
}
