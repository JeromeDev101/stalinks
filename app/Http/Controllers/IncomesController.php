<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Publisher;

class IncomesController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $user = Auth::user();
        $list = Backlink::with(['publisher' => function($query){
            $query->with('user:id,name');
        }, 'user'])
                    ->where('status', 'Live')
                    ->orderBy('created_at', 'desc');

        $registered = Registration::where('email', $user->email)->first();
        $publisher_ids = Publisher::where('user_id', $user->id)->pluck('id')->toArray();

        if( $user->type != 10 && $registered->type == 'Seller' ){
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

        return [
            'data' => $list->get()
        ];
    }

    public function update(Request $request){
        $input = $request->only('status', 'payment_status', 'live_date');
        $backlink = Backlink::findOrFail($request->id);
        $backlink->update($input);

        return response()->json(['success'=> true], 200);
    }
}
