<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;

class IncomesAdminController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $paginate = isset($filter['paginate']) && !empty($filter['paginate']) ? $filter['paginate']:50;

        $list = Backlink::where('status', 'Live');

        if( isset($filter['backlink_id']) && $filter['backlink_id'] != ''){
            $list = $list->where('id', $filter['backlink_id']);
        }

        $list = $list->orderBy('id', 'desc')->paginate($paginate);

        return response()->json($list);
    }
}
