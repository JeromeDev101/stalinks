<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Publisher;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;

class BuyController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $list = DB::table('publisher')
                    ->select('publisher.*','users.name', 'users.isOurs', 'registration.company_name', 'countries.name AS country_name')
                    ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                    ->leftJoin('registration', 'users.email', '=', 'registration.email')
                    ->leftJoin('countries', 'publisher.language_id', '=', 'countries.id')
                    ->orderBy('created_at', 'desc');
        
        if( isset($filter['search']) && !empty($filter['search']) ){
            $list = $list->where('registration.company_name', 'like', '%'.$filter['search'].'%')
                    ->orWhere('users.name', 'like', '%'.$filter['search'].'%');
        }

        if( isset($filter['language_id']) && !empty($filter['language_id']) ){
            $list = $list->where('language_id', $filter['language_id']);
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
            'date_process' => date('Y-m-d')
        ]);

        return response()->json(['success'=> true], 200);
    }
}
