<?php

namespace App\Http\Controllers;

use App\Events\BacklinkStatusChangedEvent;
use App\Repositories\Contracts\NotificationInterface;
use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Publisher;
use App\Events\BacklinkLiveEvent;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

class FollowupSalesController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $paginate = isset($filter['paginate']) && !empty($filter['paginate']) ? $filter['paginate']:50;
        $user = Auth::user();
        $list = Backlink::select('backlinks.*', 'publisher.url as publisher_url','B.username as in_charge', 'article.id as article_id')
                        ->leftJoin('publisher', 'backlinks.publisher_id' , '=', 'publisher.id')
                        ->leftJoin('countries', 'publisher.country_id', '=','countries.id')
                        ->leftJoin('users as A', 'publisher.user_id', '=', 'A.id')
                        ->leftJoin('registration', 'A.email', '=', 'registration.email')
                        ->leftJoin('users as B', 'registration.team_in_charge', '=', 'B.id')
                        ->leftJoin('article', 'backlinks.id' , '=', 'article.id_backlink')
                        ->with(['publisher' => function($query) {
                            $query->with('user:id,name,username')->with('country:id,name');
                        }])
                        ->with('user:id,name,username')
                        ->orderBy('created_at', 'desc');

        $registered = Registration::where('email', $user->email)->first();
        $publisher_ids = Publisher::where('user_id', $user->id)->pluck('id')->toArray();

        if( $user->type != 10 && isset($registered->type) && $registered->type == 'Seller' ){
            $list->whereIn('backlinks.publisher_id', $publisher_ids);
        }

        if( isset($filter['article']) && !empty($filter['article']) ){
            if( $filter['article'] == 'With'){
                $list->whereNotNull('article.id_backlink');
            }
            if( $filter['article'] == 'Without'){
                $list->whereNull('article.id_backlink');
            }
        }

        if( isset($filter['country_id']) && !empty($filter['country_id']) ){
            $list->where('countries.id', $filter['country_id']);
        }

        if( isset($filter['backlink_id']) && !empty($filter['backlink_id']) ){
            $list->where('backlinks.id', $filter['backlink_id']);
        }

        if( isset($filter['in_charge']) && !empty($filter['in_charge']) ){
            $list->where('B.id', $filter['in_charge']);
        }

        if( isset($filter['seller']) && !empty($filter['seller']) ){
            $list->where('publisher.user_id', $filter['seller']);
        }

        if(isset($filter['buyer']) && !empty($filter['buyer']) ){
            $list->where('backlinks.user_id', $filter['buyer']);
        }

        if( isset($filter['status']) && !empty($filter['status']) ){
            if (!is_array($filter['status'])) {
                $list->where('backlinks.status', $filter['status'] );
            } else {
                $list->whereIn('backlinks.status', $filter['status'] );
            }
        }

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list->where('publisher.url', 'like','%'.$filter['search'].'%' );
        }

        $data = $list->paginate($paginate);

        return $data;

    }

    public function update( Request $request, NotificationInterface $notification){
        $seller = DB::table('backlinks')
                    ->join('publisher','backlinks.publisher_id','=','publisher.id')
                    ->join('users','publisher.user_id','=','users.id')
                    ->select('users.id as user_id','users.email as user_primary_email','users.work_mail as user_work_mail')
                    ->where('backlinks.id',$request->id)
                    ->first();

        $input = $request->only('status', 'url_from', 'link_from', 'sales', 'title', 'reason', 'reason_detailed');
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

            $notification->create([
                'user_id' => $backlink->user_id,
                'notification' => 'Your purchased Backlink ID ' . $backlink->id . ' from ' . $backlink->url_advertiser . ' is Live.',
            ]);

            broadcast(new BacklinkLiveEvent($backlink->user_id));
            $input['live_date'] = date('Y-m-d');
        }

        $notification->create([
            'user_id' => $backlink->publisher->user_id,
            'notification' => 'Your order number ' . $backlink->id . ' from ' . $backlink->url_advertiser . ' has now the status ' . $input['status'],
        ]);

        broadcast(new BacklinkStatusChangedEvent($backlink->publisher->user_id));

        $backlink->update($input);
        return response()->json(['success'=> true], 200);
    }
}
