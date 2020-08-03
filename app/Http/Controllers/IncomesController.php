<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Publisher;
use App\Models\BuyerPurchased;

class IncomesController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $user = Auth::user();
        $list = Backlink::select('backlinks.*', 'billing.proof_doc_path', 'billing.admin_confirmation')
                    ->leftJoin('billing', 'backlinks.id', '=', 'billing.id_backlink')
                    ->with(['publisher' => function($query){
                        $query->with('user:id,name');
                    }])
                    ->with('user:id,name')
                    ->where('status', 'Live')
                    ->orderBy('created_at', 'desc');

        $registered = Registration::where('email', $user->email)->first();
        $publisher_ids = Publisher::where('user_id', $user->id)->pluck('id')->toArray();

        if( ($user->type != 10 && $user->role->description != 'Manager') && (isset($registered->type) && $registered->type) == 'Seller' ){
            $list->whereIn('publisher_id', $publisher_ids);
        }

        if( isset($filter['user']) && $filter['user'] != ''){
            $user = $filter['user'];
            $list->whereHas('user', function($query) use ($user){
                return $query->where('name', 'like', '%'.$user.'%');
            });
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

    public function update(Request $request){
        $input = $request->only('status', 'payment_status', 'live_date');
        $backlink = Backlink::findOrFail($request->id);
        $backlink->update($input);

        return response()->json(['success'=> true], 200);
    }
}
