<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Publisher;
use App\Models\BuyerPurchased;
use App\Models\WalletTransaction;
use App\Models\User;

class PurchaseController extends Controller
{
    /**
     * Get list of Backlinks with purchase
     *
     * @return list
     */
    public function getList(Request $request) {

        $user = Auth::user();
        $filter = $request->all();
        $paginate = isset($filter['paginate']) && !empty($filter['paginate']) ? $filter['paginate']:50;
        $wallet = 0;
        $deposit = 0;
        $user_ids = [];

        $filter['date_completed'] = json_decode($filter['date_completed']);

        $list = Backlink::select('backlinks.*')
            ->leftJoin('publisher', 'publisher.id', '=', 'backlinks.publisher_id')
            ->with(['publisher' => function($query){
                $query->with('user:id,name,username');
            }])
            ->with('user:id,name,username')
            ->where('status', 'Live')
            ->orderBy('id', 'desc');

        if( !$user->isAdmin() && $user->role->id != 7 ){
            $user_ids[] = $user->id;
        }

        if( isset($filter['search_id']) && $filter['search_id'] != ''){
            $list->where('backlinks.id', $filter['search_id']);
        }

        if( isset($filter['search_url_publisher']) && $filter['search_url_publisher'] != ''){
            $list->where('publisher.url', 'like', '%'.$filter['search_url_publisher'].'%');
        }

        if( isset($filter['payment_status']) && $filter['payment_status'] != ''){
            $list->where('backlinks.payment_status', $filter['payment_status']);
        }

        if( isset($filter['buyer']) && $filter['buyer'] != ''){
            $buyer = $filter['buyer'];
            $list->whereHas('user', function($query) use ($buyer){
                return $query->where('id', $buyer);
            });
        }

        if( isset($filter['seller']) && $filter['seller'] != ''){
            $seller = $filter['seller'];
            $list->whereHas('publisher', function($query) use ($seller){
                return $query->where('user_id', $seller);
            });
        }

        if( !empty($filter['date_completed']) && $filter['date_completed']->startDate != ''){
            $list->where('live_date', '>=', Carbon::create($filter['date_completed']->startDate)
                ->format('Y-m-d'));
            $list->where('live_date', '<=', Carbon::create($filter['date_completed']->endDate)
                ->format('Y-m-d'));
        }

        $user_id = $user->id;

        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user_id)->pluck('email');
        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');

        $registered = Registration::where('email', Auth::user()->email)->first();
        if ( isset($registered->is_sub_account) && $registered->is_sub_account == 1 ) {
            if ( isset($registered->team_in_charge) ) {
                $user_model = User::where('id', $registered->team_in_charge)->first();
                $user_id = isset($user_model->id) ? $user_model->id : Auth::user()->id;
            }
        }

        if (count($sub_buyer_ids) > 0) {
//            $list->orWhereIn('backlinks.user_id', $sub_buyer_ids)->where('backlinks.status', 'Live');
            $user_ids = array_merge($user_ids, $sub_buyer_ids->toArray());
        }

        if($user_ids){
            $list->whereIn('backlinks.user_id', $user_ids)->where('backlinks.status', 'Live');
        }

        // Getting wallet and deposits

        $getBuyerSubs = Registration::select('users.id as user_id_buyer', 'users.username')
            ->leftJoin('users', 'users.email', '=', 'registration.email')
            ->where('registration.team_in_charge', $user_id)
            ->where('registration.is_sub_account', 1)
            ->get();

        $getBuyer = BuyerPurchased::select('buyer_purchased.user_id_buyer','users.username')
                                ->leftJoin('users', 'users.id', '=', 'buyer_purchased.user_id_buyer')
                                ->where('buyer_purchased.status', 'Purchased')
                                ->groupBy('buyer_purchased.user_id_buyer', 'users.username')
                                ->orderBy('users.username', 'asc')
                                ->get();

        $getSeller = Publisher::select('publisher.user_id', 'users.username')
                            ->leftJoin('users', 'users.id', '=', 'publisher.user_id')
                            ->groupBy('publisher.user_id', 'users.username')
                            ->orderBy('users.username', 'asc')
                            ->get();

        $wallet_transaction = WalletTransaction::selectRaw('SUM(amount_usd) as amount_usd')
                            ->where('user_id', $user_id)
                            ->get();

        $total_paid = Backlink::selectRaw('SUM(price) as total_paid')
                            ->where('status', 'Live')
                            ->where('payment_status', 'Paid')
                            ->where('user_id', $user_id)
                            ->when(count($sub_buyer_ids) > 0, function($query) use ($sub_buyer_ids){
                                return $query->orWhereIn('user_id', $sub_buyer_ids);
                            })
                            ->get();

        if( isset($total_paid[0]['total_paid']) && isset($wallet_transaction[0]['amount_usd']) ){
            $wallet = floatval($wallet_transaction[0]['amount_usd']) - floatval($total_paid[0]['total_paid']);
        }

        if( isset($wallet_transaction[0]['amount_usd']) ){
            $deposit = floatval($wallet_transaction[0]['amount_usd']);
        }

        // end of getting wallet and deposits

        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){}
        else{
            $list = $list->paginate($paginate);
        }

        $buyers = collect(['buyers' => $user->isAdmin() ? $getBuyer : $getBuyerSubs]);
        $sellers = collect(['sellers' => $getSeller]);
        $wallets = collect(['wallet' => round($wallet)]);
        $deposits = collect(['deposit' => round($deposit)]);

        $data_buyer = $buyers->merge($list);
        $data_seller = $sellers->merge($data_buyer);
        $data_wallet = $wallets->merge($data_seller);
        $data = $deposits->merge($data_wallet);

        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            return [
                "data" => $list->get(),
                "total" => $list->count(),
                "buyers" => $user->isAdmin() ? $getBuyer : $getBuyerSubs,
                "sellers" => $getSeller,
                "wallet" => round($wallet),
                "deposit" => round($deposit),
            ];
        }else{
            return response()->json($data);
        }
    }

    public function testRemoveHttp() {
        $publishers = Publisher::where('url', 'like', '%/%')->take(100)->get();

        foreach($publishers as $publisher) {
            $url_copy = $this->remove_http($publisher->url);
            $url = str_replace( '/','',preg_replace('/^www\./i', '', $url_copy));

            $pub = Publisher::findOrfail($publisher->id);
            $pub->update(['url' => $url]);

        }


        $publishers = Publisher::select('id', 'url')->where('url', 'like', '%/%')->get();

        return $publishers;
    }

    private function remove_http($url) {
        $disallowed = array('http://', 'https://', 'www.');
        foreach($disallowed as $d) {
           if(strpos($url, $d) === 0) {
              return str_replace($d, '', $url);
           }
        }
        return $url;
    }

}
