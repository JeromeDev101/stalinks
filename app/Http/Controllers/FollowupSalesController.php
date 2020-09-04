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
        $paginate = isset($filter['paginate']) && !empty($filter['paginate']) ? $filter['paginate']:25;
        $user = Auth::user();
        $list = Backlink::select('backlinks.*', 'publisher.url as publisher_url')
                        ->leftJoin('publisher', 'backlinks.publisher_id' , '=', 'publisher.id')
                        ->with(['publisher' => function($query) {
                            $query->with('user:id,name,username');
                        }])
                        ->with('article')
                        ->with('user:id,name,username')
                        ->orderBy('created_at', 'desc');

        $registered = Registration::where('email', $user->email)->first();
        $publisher_ids = Publisher::where('user_id', $user->id)->pluck('id')->toArray();

        if( $user->type != 10 && isset($registered->type) && $registered->type == 'Seller' ){
            $list->whereIn('backlinks.publisher_id', $publisher_ids);
        }

        if( isset($filter['seller']) && !empty($filter['seller']) ){
            $list->where('publisher.user_id', $filter['seller']);
        }

        if( isset($filter['buyer']) && !empty($filter['buyer']) ){
            $list->where('backlinks.user_id', $filter['buyer']);
        }

        if( isset($filter['status']) && !empty($filter['status']) ){
            $list->where('backlinks.status', $filter['status'] );
        }

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list->where('publisher.url', 'like','%'.$filter['search'].'%' );
        }

        $data = $list->paginate($paginate);

        return $data;

    }

    public function update( Request $request ){
        $input = $request->only('status', 'url_from', 'link_from', 'sales', 'title');
        $backlink = Backlink::findOrFail($request->id);
        $input['payment_status'] = 'Not Paid';
        if( $input['status'] == 'Live' ){
            if( empty($request->title) && empty($request->link_from) ){
                return response()->json([
                    "message" => 'Incomplete input',
                    "errors" => [
                        "title" => 'Please add Title',
                        "link_from" => 'Please add Link From'
                    ],
                ],422);
            }

            $input['live_date'] = date('Y-m-d');
        }
        $backlink->update($input);

        return response()->json(['success'=> true], 200);
    }
}
