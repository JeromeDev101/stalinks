<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;
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
                    $sub->with('UserType.affiliate:id,status');
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

        // if affiliate - get only data for his buyers

        if (Auth::user()->role_id === 11) {
            $affiliate_buyer_ids = $this->getAffiliateBuyers(Auth::id());

            $list = $list->whereIn('backlinks.user_id', $affiliate_buyer_ids);
        }

        // get the list first for billing count and affiliate commission

        $temp = $list->get();

        $sellerPrice = $list->sum('price');
        $buyerPrice = $list->sum('prices');
        $billingCount = $temp->sum('billing_count');
        $affiliateCommission = $temp->sum('affiliate_commission');

        if($paginate === 'All'){
            $list = [
                'data' => $list->orderBy('id', 'desc')->get(),
                'total' => $list->orderBy('id', 'desc')->count(),
            ];
        } else {
            $list = $list->orderBy('id', 'desc')->paginate($paginate);
        }

        $totals = collect([
            'seller_price_sum' => $sellerPrice,
            'buyer_price_sum' => $buyerPrice,
            'billing_count_sum' => $billingCount,
            'affiliate_commission_sum' => $affiliateCommission,
        ]);

        return response()->json($totals->merge($list));
    }

    protected function getAffiliateBuyers($affiliate_id)
    {
        $affiliate_buyer_ids = [];

        if ($affiliate_id) {
            $affiliate_buyer_emails = Registration::where('affiliate_id', $affiliate_id)
                ->where('status', 'active')
                ->pluck('email')
                ->toArray();

            if ($affiliate_buyer_emails) {
                $affiliate_buyer_ids = User::where('email', $affiliate_buyer_emails)->pluck('id')->toArray();
            }
        }

        return $affiliate_buyer_ids;
    }
}
