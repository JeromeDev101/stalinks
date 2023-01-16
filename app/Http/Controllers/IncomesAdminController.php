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

//        $list = Backlink::select('backlinks.*', 'registration.rate_type', 'registration.writer_price')
//                ->where('backlinks.status', 'Live')
//                ->leftJoin('article', 'backlinks.id', '=', 'article.id_backlink')
//                ->leftJoin('users', 'article.id_writer', '=', 'users.id')
//                ->leftjoin('registration', 'users.email', '=' , 'registration.email')
//                ->with(['user' => function($sub) {
//                    $sub->with('UserType.affiliate:id,status');
//                }])
//                ->with('article')
//                ->with('publisher')
//                ->withCount(['billing' => function ($query) {
//                    return $query->select(DB::raw('SUM(fee) AS fee'));
//                }]);

        $list = $this->writeIncomesQuery();


        if( isset($filter['backlink_id']) && $filter['backlink_id'] != ''){
            $list = $list->where('backlinks.id', $filter['backlink_id']);
        }

        if( isset($filter['buyer_id']) && $filter['buyer_id'] != ''){
            $list = $list->where('backlinks.user_id', $filter['buyer_id']);
        }

        if( isset($filter['buyer']) && $filter['buyer'] != '' && is_array($filter['buyer'])){
            $list = $list->whereIn('backlinks.user_id', $filter['buyer']);
        }

        if (isset($filter['date_completed'])) {
            $filter['date_completed'] = \GuzzleHttp\json_decode($filter['date_completed'], true);

            if ($filter['date_completed']['startDate'] != null && $filter['date_completed']['endDate'] != null) {
                $list = $list->whereDate('backlinks.live_date', '>=', Carbon::create( $filter['date_completed']['startDate'])->format('Y-m-d'));
                $list = $list->whereDate('backlinks.live_date', '<=', Carbon::create($filter['date_completed']['endDate'])->format('Y-m-d'));
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
        $affiliateCommission = $temp->sum('affiliate_com');
        $netIncomes = $temp->sum('net_income');
        $feeCharges = $temp->sum('fee');
        $contentCharges = $temp->sum('content_charge');

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
            'net_incomes_sum' => $netIncomes,
            'fee_charges_sum' => $feeCharges,
            'content_charges_sum' => $contentCharges,
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

    public function getOverallIncomesBuyers () {
        $list = Backlink::select('users.id', 'users.name', 'users.email', 'users.username')
            ->join('users', 'backlinks.user_id', 'users.id')
            ->orderBy('users.username')
            ->groupBy('backlinks.user_id', 'users.id', 'users.name', 'users.email')
            ->where('backlinks.status', 'Live')
            ->get();

        $datas = [];
        foreach($list as $data) {

            $registration = Registration::with('team_in_charge_username')
                                ->where('is_sub_account', 1)
                                ->where('email', $data->email)
                                ->first();
            $in_charge = '';
            if($registration) {
                $in_charge = ' ['.$registration['team_in_charge_username']['username'].']';
            }

            array_push($datas, [
                'id' => $data->id,
                'name' => $data->name,
                'email' => $data->email,
                'username' => $data->username.$in_charge
            ]);
        }

        return response()->json([
            'data' => $datas
        ]);
    }

    protected function writeIncomesQuery ()
    {
        $percentage = settings('affiliate_percentage') ?: 0;
        $users_with_affiliates = get_buyer_id_with_affiliates();

        if ($users_with_affiliates) {
            $affiliate_when = 'WHEN backlinks.user_id NOT IN (' . implode(',', $users_with_affiliates) . ') THEN 0 ELSE (' . $percentage . '/100) * @net_income';
        } else {
            $affiliate_when = 'ELSE 0';
        }

        return Backlink::selectRaw("
            backlinks.*,
            article.id as article_id,
            article.num_words,

            @writer_price := CASE
              WHEN registration.writer_price IS NULL OR registration.writer_price = '' THEN 0
              ELSE registration.writer_price
            END as writer_price,

            @rate_type := CASE
              WHEN registration.rate_type IS NULL OR registration.rate_type = '' THEN 'ppw'
              ELSE LOWER(registration.rate_type)
            END as 'rate_type',

            @fee := CASE
              WHEN (select SUM(fee) AS fee from `billing` where `backlinks`.`id` = `billing`.`id_backlink`)
               IS NULL THEN 0
              ELSE (select SUM(fee) AS fee from `billing` where `backlinks`.`id` = `billing`.`id_backlink`)
            END as fee,

            @content_charge := CASE
              WHEN registration.writer_price IS NULL OR article.id IS NULL THEN 0
              ELSE
                CASE
                    WHEN @rate_type = 'ppw' THEN (@writer_price * article.num_words)
                    ELSE @writer_price
                END
            END as 'content_charge',

            @net_income := backlinks.prices - backlinks.price - @content_charge - @fee as 'net_income',

            CASE
              WHEN backlinks.prices IS NULL OR backlinks.prices = '' THEN 0
              ". $affiliate_when . "
            END as 'affiliate_com'
        ")
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
    }
}
