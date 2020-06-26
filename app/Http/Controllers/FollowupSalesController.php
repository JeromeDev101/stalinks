<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Publisher;

class FollowupSalesController extends Controller
{
    public function getList(){
        $user = Auth::user();
        $list = Backlink::with(['publisher' => function($query) {
            $query->with('user:id,name');
        }, 'user'])
                    ->orderBy('created_at', 'desc');
        
        $registered = Registration::where('email', $user->email)->first();
        $publisher_ids = Publisher::where('user_id', $user->id)->pluck('id')->toArray();

        if( $user->type != 10 && $registered->type == 'Seller' ){
            $list->whereIn('publisher_id', $publisher_ids);
        }

        return [
            'data' => $list->get()
        ];
    }

    public function update( Request $request ){
        $input = $request->only('status', 'url_from', 'link_from');
        $backlink = Backlink::findOrFail($request->id);
        $input['payment_status'] = 'Not Paid';
        $backlink->update($input);

        return response()->json(['success'=> true], 200);
    }
}
