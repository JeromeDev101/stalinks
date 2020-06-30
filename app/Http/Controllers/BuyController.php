<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Publisher;
use App\Models\Backlink;
use App\Models\BuyerPurchased;
use Illuminate\Support\Facades\Auth;

class BuyController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $user_id = Auth::user()->id;
        $list = DB::table('publisher')
                    ->select('publisher.*','users.name', 'users.isOurs', 'registration.company_name', 'countries.name AS country_name', 'buyer_purchased.status as status_purchased')
                    ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                    ->leftJoin('registration', 'users.email', '=', 'registration.email')
                    ->leftJoin('countries', 'publisher.language_id', '=', 'countries.id')

                    ->leftJoin('buyer_purchased', 'publisher.id', '=', 'buyer_purchased.publisher_id')

                    // ->leftJoin('buyer_purchased', function($query){
                    //     $query->where('publisher.id', '=', 'buyer_purchased.publisher_id');
                    // })
                    ->orderBy('created_at', 'desc');

        // $list = Publisher::with(['user' => function($query){
        //     $query->with('UserType:id,company_name');
        // }])->with('country:id,name');
        
        if( isset($filter['search']) && !empty($filter['search']) ){
            $list = $list->where('registration.company_name', 'like', '%'.$filter['search'].'%')
                    ->orWhere('users.name', 'like', '%'.$filter['search'].'%');
        }

        if( isset($filter['language_id']) && !empty($filter['language_id']) ){
            $list = $list->where('language_id', $filter['language_id']);
        }

        if( isset($filter['status_purchase']) && !empty($filter['status_purchase']) ){
            if( $filter['status_purchase'] == 'New'){
                $list = $list->whereNull('buyer_purchased.publisher_id');
            }else{
                $list = $list->where('buyer_purchased.status', $filter['status_purchase']);
            }   
        }


        return [
            'data' => $list->get()
        ];
    }

    public function update(Request $request) {
        $publisher = Publisher::findOrFail($request->id);
        $user = Auth::user();

        Backlink::create([
            'price' => $request->price,
            'anchor_text' => $request->anchor_text,
            'link' => $request->link,
            'publisher_id' => $publisher->id,
            'user_id' => $user->id,
            'status' => 'Processing',
            'date_process' => date('Y-m-d'),
            'ext_domain_id' => 0,
            'int_domain_id' => 0,
        ]);

        BuyerPurchased::create([
            'user_id_buyer' => $user->id,
            'publisher_id' => $publisher->id,
            'status' => 'Purchased',
        ]);

        return response()->json(['success'=> true], 200);
    }

    public function updateDislike(Request $request) {
        $user = Auth::user();
        $publisher = Publisher::findOrFail($request->id);

        BuyerPurchased::create([
            'user_id_buyer' => $user->id,
            'publisher_id' => $publisher->id,
            'status' => 'Not interested',
        ]);

        return response()->json(['success'=> true], 200);
    }
}
