<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;

class IncomesController extends Controller
{
    /**
     * get list of backlink
     *
     * @return json
     */
    public function getList(Request $request){
        $filter = $request->all();
        $list = Backlink::with('publisher', 'extDomain', 'user')
                    ->where('status', 'Live')
                    ->orderBy('created_at', 'desc');

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
