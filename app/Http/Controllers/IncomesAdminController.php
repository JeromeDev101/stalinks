<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\DB;

class IncomesAdminController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $paginate = isset($filter['paginate']) && !empty($filter['paginate']) ? $filter['paginate']:50;

        $list = Backlink::where('status', 'Live')
                ->with('publisher')
                ->withCount(['billing' => function ($query) {
                    return $query->select(DB::raw('SUM(fee) AS fee'));
                }]);
                

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
        $sellerPrice = $list->sum('prices');
        $buyerPrice = $list->sum('price');


        $list = $list->orderBy('id', 'desc')->paginate($paginate);

        $totals = collect([
            'seller_price_sum' => number_format($sellerPrice, 2),
            'buyer_price_sum' => number_format($buyerPrice, 2)
        ]);

        return response()->json($totals->merge($list));
    }
}
