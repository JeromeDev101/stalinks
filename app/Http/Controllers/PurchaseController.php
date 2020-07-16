<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Publisher;
use App\Models\BuyerPurchased;

class PurchaseController extends Controller
{
    /**
     * Get list of Backlinks with purchase
     *
     * @return list
     */
    public function getList(Request $request) {
        $user = Auth::user();
        $filter = $request->all();

        $list = Backlink::with(['publisher' => function($query){
            $query->with('user:id,name');
        }, 'user'])
                    ->where('status', 'Live')
                    ->orderBy('created_at', 'desc');

        if( !$user->isAdmin() && $user->role->id != 7 ){
            $list->where('user_id', $user->id);
        }

        if( isset($filter['payment_status']) && $filter['payment_status'] != ''){
            $list->where('payment_status', $filter['payment_status']);
        }

        if(isset($filter['status']) && !is_null($filter['status'])) {
            $list->where('status', $filter['status']);
        }

        if( isset($filter['buyer']) && $filter['buyer'] != ''){
            $buyer = $filter['buyer'];
            $list->whereHas('user', function($query) use ($buyer){
                return $query->where('id', $buyer);
            });
        }

        if( isset($filter['seller']) && $filter['seller'] != ''){
            $seller = $filter['seller'];
            $list->whereHas('publisher', function($query) use ($seller){
                return $query->where('user_id', $seller);
            });
        }

        $getBuyer = BuyerPurchased::select('buyer_purchased.user_id_buyer','users.username')
                                ->leftJoin('users', 'users.id', '=', 'buyer_purchased.user_id_buyer')
                                ->where('buyer_purchased.status', 'Purchased')
                                ->groupBy('buyer_purchased.user_id_buyer', 'users.username')
                                ->get();

        $getSeller = Publisher::select('publisher.user_id', 'users.username')
                            ->leftJoin('users', 'users.id', '=', 'publisher.user_id')
                            ->groupBy('publisher.user_id', 'users.username')
                            ->get();

        return [
            'data' => $list->get(),
            'buyers' => $getBuyer,
            'sellers' => $getSeller
        ];
    }
}
