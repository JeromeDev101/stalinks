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
    public function getList(){
        $list = Backlink::with('publisher', 'extDomain', 'user')
                    ->where('status', 'Live')
                    ->orderBy('created_at', 'desc');

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
