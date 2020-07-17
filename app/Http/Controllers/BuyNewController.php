<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Publisher;
use App\Models\Backlink;
use App\Models\BuyerPurchased;
use Illuminate\Support\Facades\Auth;

class BuyNewController extends Controller
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

        // $list = Publisher::select($columns)
        //                 // ->leftJoin('users', 'publisher.user_id', '=', 'users.id');
        //                 ->join('users', function ($join) use ($filter) {
        //                     $join->on('publisher.user_id', '=', 'users.id')
        //                          ->when( isset($filter['search']) && !empty($filter['search']), function ($q) use ($filter) {
        //                             return $q->where('users.name', 'like', '%'. $filter['search'] . '%');
        //                          });
        //                         //  ->whereHas('registration', function ($query) use ($filter) {
        //                         //     $query->where('registration.company_name', 'like', '%'.$filter['search'].'%');
        //                         //  });
        //                         //  ->join('registration', function ($join) use ($filter) {
        //                         //     $join->on('users.email', '=', 'registration.email')
        //                         //          ->when( isset($filter['search']) && !empty($filter['search']), function ($q) use ($filter) {
        //                         //             return $q->where('registration.company_name', 'like', '%'.$filter['search'].'%');
        //                         //          });
        //                         // });
        //                 });
                        //  ->join('registration', function ($join) use ($filter) {
                        //     $join->on('users.email', '=', 'registration.email')
                        //          ->when( isset($filter['search']) && !empty($filter['search']), function ($q) use ($filter) {
                        //             return $q->where('registration.company_name', 'like', '%'.$filter['search'].'%');
                        //          });
                        // });
                        // ->leftJoin('registration', 'users.email', '=', 'registration.email');
                        // ->leftJoin('countries', 'publisher.language_id', '=', 'countries.id')
                        // ->leftJoin('buyer_purchased', 'publisher.id', '=', 'buyer_purchased.publisher_id');


                    // ->leftJoin('buyer_purchased', function($query){
                    //     $query->where('publisher.id', '=', 'buyer_purchased.publisher_id');
                    // })


        // $list = Publisher::with('user:id,name,isOurs')
        //         ->whereHas('user', function($query) use ($filter){
        //             $query->when( isset($filter['search']) && !empty($filter['search']), function($subquery) use ($filter){
        //                 return $subquery->where('users.name', 'like', '%'.$filter['search'].'%');
        //             })
        //             // ->whereHas('registration', function ($q) use ($filter) {
        //             //     $q->where('company_name', 'like', '%'.$filter['search'].'%');
        //             // });
        //              ->leftJoin('registration', function ($join) use ($filter) {
        //                 $join->on('users.email', '=', 'registration.email')
        //                      ->when( isset($filter['search']) && !empty($filter['search']), function ($q) use ($filter) {
        //                         return $q->where('registration.company_name', 'like', '%'.$filter['search'].'%');
        //                      });
        //             });
        //         });

        $list = Publisher::query()
                        ->select($columns)
                        ->join('users', 'publisher.user_id', '=', 'users.id')
                        ->leftJoin('registration', 'users.email', '=', 'registration.email')
                        ->leftJoin('buyer_purchased', 'publisher.id', '=', 'buyer_purchased.publisher_id')
                        ->leftJoin('countries', 'publisher.language_id', '=', 'countries.id')
                        ->where(function($q) use ($filter) {
                            return $q->when(isset($filter['search']) && !empty($filter['search']), function ($subquery) use ($filter)  {
                                        $subquery->where('users.name', 'like', '%'. $filter['search'] . '%')
                                                 ->orWhere('registration.company_name', 'like', '%'.$filter['search'].'%');
                                    })
                                    ->when(isset($filter['status_purchase']) && !empty($filter['status_purchase']), function ($subquery) use ($filter)  {
                                        $subquery->whereIn('buyer_purchased.status', $filter['status_purchase']);
                                        if( in_array('New', $filter['status_purchase']) ){
                                            $subquery->orWhereNull('buyer_purchased.publisher_id');
                                        }
                                    })
                                    ->when(isset($filter['language_id']) && !empty($filter['language_id']), function ($subquery) use ($filter)  {
                                        $subquery->where('language_id', $filter['language_id']);
                                    });
                        });

        // ->with('country:id,name')
        // ->with('buyer_purchased');

        // if( isset($filter['status_purchase']) && !empty($filter['status_purchase']) ){
        //     if( is_array($filter['status_purchase']) ){
        //         $list->whereIn('buyer_purchased.status', $filter['status_purchase']);
        //         if( in_array('New', $filter['status_purchase']) ){
        //             $list = $list->orWhereNull('buyer_purchased.publisher_id');
        //         }
        //     }
        // }

        // if( isset($filter['search']) && !empty($filter['search']) ){
        //     $list = $list->where('registration.company_name', 'like', '%'.$filter['search'].'%')
        //             ->orWhere('users.name', 'like', '%'.$filter['search'].'%');
        // }

        // if( isset($filter['language_id']) && !empty($filter['language_id']) ){
        //     $list = $list->where('language_id', $filter['language_id']);
        // }

        $list = $list->orderBy('publisher.created_at', 'desc');
        return [
            'data' => $list->get()
        ];
    }

    public function update(Request $request) {
        $publisher = Publisher::findOrFail($request->id);
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
        $publisher = Publisher::findOrFail($request->id);
        $this->updateStatus($request->id, 'Not interested', $publisher->id);

        return response()->json(['success'=> true], 200);
    }

    public function updateLike(Request $request) {
        $publisher = Publisher::findOrFail($request->id);
        $this->updateStatus($request->id, 'Interested', $publisher->id);

        return response()->json(['success'=> true], 200);
    }

    private function updateStatus($id, $status, $id_publisher) {
        $buyer_purchased = BuyerPurchased::where('publisher_id',$id)->first();
        $user = Auth::user();
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

    public function getBuyerSummary()
    {
        $user = Auth::user();

        $buyer = BuyerPurchased::selectRaw('publisher.language_id, count(DISTINCT(buyer_purchased.id)) as total')
                        ->leftJoin('publisher','buyer_purchased.publisher_id', '=', 'publisher.id')
                        ->where('user_id_buyer',$user->id)
                        ->where('status', 'Purchased')
                        ->groupBy('publisher.language_id')
                        ->distinct()
                        ->get();

        $sumTotal = 0;

        foreach($buyer as $item) {
            $sumTotal += $item->total;
        }

        return [
            'total' => $sumTotal,
            'data' => $buyer
        ];
    }

}
