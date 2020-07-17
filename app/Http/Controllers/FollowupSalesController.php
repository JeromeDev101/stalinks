<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Publisher;

class FollowupSalesController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $user = Auth::user();
        $list = Backlink::select('backlinks.*', 'publisher.url as publisher_url')
                        ->leftJoin('publisher', 'backlinks.publisher_id' , '=', 'publisher.id')
                        ->with(['publisher' => function($query) {
                            $query->with('user:id,name');
                        }])
                        ->with('user:id,name')
                        ->orderBy('created_at', 'desc');

        $registered = Registration::where('email', $user->email)->first();
        $publisher_ids = Publisher::where('user_id', $user->id)->pluck('id')->toArray();

        if( $user->type != 10 && $registered->type == 'Seller' ){
            $list->whereIn('publisher_id', $publisher_ids);
        }

        if( isset($filter['status']) && !empty($filter['status']) ){
            $list->where('status', $filter['status'] );
        }

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list->where('publisher.url', 'like','%'.$filter['search'].'%' );
        }

        return [
            'data' => $list->get()
        ];
    }

    public function update( Request $request ){
        $input = $request->only('status', 'url_from', 'link_from', 'sales');
        $backlink = Backlink::findOrFail($request->id);
        $input['payment_status'] = 'Not Paid';
        if( $input['status'] == 'Live' ){
            $input['live_date'] = date('Y-m-d');
        }
        $backlink->update($input);

        return response()->json(['success'=> true], 200);
    }
}
