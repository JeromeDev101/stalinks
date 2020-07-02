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

        $columns = [
            'publisher.*',
            'users.name', 
            'users.isOurs', 
            'registration.company_name', 
            'countries.name AS country_name', 
            'buyer_purchased.status as status_purchased'
        ];

        $list = Publisher::query()
                        ->select($columns)
                        ->join('users', 'publisher.user_id', '=', 'users.id')
                        ->leftJoin('registration', 'users.email', '=', 'registration.email')
                        ->leftJoin('buyer_purchased', function($q) use ($user_id){
                            $q->on('publisher.id', '=', 'buyer_purchased.publisher_id')
                                ->where('buyer_purchased.user_id_buyer', $user_id);
                        })
                        ->leftJoin('countries', 'publisher.language_id', '=', 'countries.id')
                        ->where(function($q) use ($filter) {
                            return $q->when(isset($filter['search']) && !empty($filter['search']), function ($subquery) use ($filter)  {
                                        $subquery->where('users.name', 'like', '%'. $filter['search'] . '%')
                                                 ->orWhere('registration.company_name', 'like', '%'.$filter['search'].'%');
                                    })
                                    ->when(isset($filter['status_purchase']) && !empty($filter['status_purchase']), function ($subquery) use ($filter)  {
                                        if( is_array($filter['status_purchase']) ){

                                            $subquery->whereIn('buyer_purchased.status', $filter['status_purchase']);

                                            if( in_array('New', $filter['status_purchase']) ){
                                                $subquery->orWhereNull('buyer_purchased.publisher_id');
                                            }
                                        }
                                    })
                                    ->when(isset($filter['language_id']) && !empty($filter['language_id']), function ($subquery) use ($filter)  {
                                        $subquery->where('publisher.language_id', $filter['language_id']);
                                    });
                        });

        $list = $list->orderBy('publisher.created_at', 'desc');

        return [
            'data' => $list->get()
        ];
    }

    public function update(Request $request) {
        $publisher = Publisher::find($request->id);
        $user = Auth::user();

        $this->updateStatus($request->id, 'Purchased', $publisher->id);
        
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

        return response()->json(['success'=> true], 200);
    }

    public function updateDislike(Request $request) {
        $publisher = Publisher::find($request->id);
        $this->updateStatus($request->id, 'Not interested', $publisher->id);

        return response()->json(['success'=> true], 200);
    }

    public function updateLike(Request $request) {
        $publisher = Publisher::find($request->id);
        $this->updateStatus($request->id, 'Interested', $publisher->id);

        return response()->json(['success'=> true], 200);
    }

    private function updateStatus($id, $status, $id_publisher) {
        $user = Auth::user();
        $buyer_purchased = BuyerPurchased::where('publisher_id',$id)->where('user_id_buyer', $user->id)->first();

        if( !$buyer_purchased ){
            BuyerPurchased::create([
                'user_id_buyer' => $user->id,
                'publisher_id' => $id_publisher,
                'status' => $status,
            ]);
        }else{
            $buyer_purchased->update(['status' => $status]);
        }
    }

}
