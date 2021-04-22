<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Backlink;

class IncomesAdminController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $paginate = isset($filter['paginate']) && !empty($filter['paginate']) ? $filter['paginate']:50;

        $list = Backlink::where('status', 'Live')
                ->with('publisher');

        if( isset($filter['backlink_id']) && $filter['backlink_id'] != ''){
            $list = $list->where('id', $filter['backlink_id']);
        }

        if (isset($filter['date_completed'])) {
            $filter['date_completed'] = \GuzzleHttp\json_decode($filter['date_completed'], true);

            if ($filter['date_completed']['startDate'] != null && $filter['date_completed']['endDate'] != null) {
                $list = $list->where('backlinks.live_date', '>=', Carbon::create( $filter['date_completed']['startDate'])->format('Y-m-d'));
                $list = $list->where('backlinks.live_date', '<=', Carbon::create($filter['date_completed']['endDate'])->format('Y-m-d'));
            }
        }

        $list = $list->orderBy('id', 'desc')->paginate($paginate);

        return response()->json($list);
    }
}
