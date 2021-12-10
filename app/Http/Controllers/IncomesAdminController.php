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

        $list = Backlink::select('backlinks.*', 'registration.rate_type', 'registration.writer_price')
                ->where('backlinks.status', 'Live')
                ->leftJoin('article', 'backlinks.id', '=', 'article.id_backlink')
                ->leftJoin('users', 'article.id_writer', '=', 'users.id')
                ->leftjoin('registration', 'users.email', '=' , 'registration.email')
                ->with(['user' => function($sub) {
                    $sub->with('UserType');
                }])
                ->with('article')
                ->with('publisher')
                ->withCount(['billing' => function ($query) {
                    return $query->select(DB::raw('SUM(fee) AS fee'));
                }]);


        if( isset($filter['backlink_id']) && $filter['backlink_id'] != ''){
            $list = $list->where('backlinks.id', $filter['backlink_id']);
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
        $billingCount = $list->get()->sum('billing_count');


        $list = $list->orderBy('id', 'desc')->paginate($paginate);

        $totals = collect([
            'seller_price_sum' => $sellerPrice,
            'buyer_price_sum' => $buyerPrice,
            'billing_count_sum' => $billingCount
        ]);

        return response()->json($totals->merge($list));
    }
}
