<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;

class FollowupSalesController extends Controller
{
    public function getList(){
        $list = Backlink::with('publisher', 'user')
                    ->orderBy('created_at', 'desc');

        return [
            'data' => $list->get()
        ];
    }

    public function update( Request $request ){
        $input = $request->only('status');
        $backlink = Backlink::findOrFail($request->id);
        $input['payment_status'] = 'Not Paid';
        $backlink->update($input);

        return response()->json(['success'=> true], 200);
    }
}
