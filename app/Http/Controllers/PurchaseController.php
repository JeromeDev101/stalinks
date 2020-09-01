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
        $paginate = isset($filter['paginate']) && !empty($filter['paginate']) ? $filter['paginate']:15;
        $list = Backlink::select('backlinks.*')
                    ->leftJoin('publisher', 'publisher.id', '=', 'backlinks.publisher_id')
                    ->with(['publisher' => function($query){
                        $query->with('user:id,name');
                    }])
                    ->with('user:id,name')
                    ->where('status', 'Live')
                    ->orderBy('id', 'desc');

        if( !$user->isAdmin() && $user->role->id != 7 ){
            $list->where('backlinks.user_id', $user->id);
        }

        if( isset($filter['search_id']) && $filter['search_id'] != ''){
            $list->where('backlinks.id', $filter['search_id']);
        }

        if( isset($filter['search_url_publisher']) && $filter['search_url_publisher'] != ''){
            $list->where('publisher.url', 'like', '%'.$filter['search_url_publisher'].'%');
        }

        if( isset($filter['payment_status']) && $filter['payment_status'] != ''){
            $list->where('backlinks.payment_status', $filter['payment_status']);
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

        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
        }else{
            $list = $list->paginate($paginate);
        }

        $buyers = collect(['buyers' => $getBuyer]);
        $sellers = collect(['sellers' => $getSeller]);

        $data = $buyers->merge($list);
        $data = $sellers->merge($data);

        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            return [
                "data" => $list->get(),
                "total" => $list->count(),
                "buyers" => $getBuyer,
                "sellers" => $getSeller,
            ];
        }else{
            return response()->json($data);
        }



        // return [
        //     'data' => $list->get(),
        //     'buyers' => $getBuyer,
        //     'sellers' => $getSeller
        // ];
    }
}
