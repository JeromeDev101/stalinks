<?php

namespace App\Http\Controllers;

use App\Events\BuyEvent;
use App\Events\SellerReceivesOrderEvent;
use App\Events\BacklinkStatusChangedEvent;
use App\Models\BuyerPurchased;
use App\Repositories\Contracts\BackLinkRepositoryInterface;
use App\Repositories\Contracts\NotificationInterface;
use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\Traits\BuyTrait;
use App\Repositories\Traits\NotificationTrait;
use App\Services\HttpClient;
use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\Models\Registration;
use App\Models\WalletTransaction;
use App\Models\User;
use App\Events\NotificationEvent;
use Illuminate\Support\Facades\DB;

class BuyController extends Controller
{
    use BuyTrait, NotificationTrait;

    protected $publisherRepository, $backlinkRepository, $httpClient;

    public function __construct(PublisherRepositoryInterface $publisherRepository, BackLinkRepositoryInterface $backLinkRepository, HttpClient $httpClient)
    {
        $this->publisherRepository = $publisherRepository;
        $this->backlinkRepository = $backLinkRepository;
        $this->httpClient = $httpClient;
    }

    public function getList(Request $request){
        $filter = $request->all();
        $paginate = (isset($filter['paginate']) && !empty($filter['paginate']) ) ? $filter['paginate']:50;
        $user = Auth::user();

        $credit = 0;

        $columns = [
            'publisher.*',
            'registration.username',
            'users.name',
            'users.username as user_name',
            'users.isOurs',
            'registration.company_name',
            'countries.name AS country_name',
            'country_continent.name AS country_continent',
            'publisher_continent.name AS publisher_continent',
            'languages.name AS language_name',
            'buyer_purchased.status as status_purchased',
            DB::raw('org_keywords/org_traffic as ratio_value')
        ];

        $list = Publisher::select($columns)
                ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                ->leftJoin('registration', 'users.email', '=', 'registration.email')
                ->leftJoin('countries', 'publisher.country_id', '=', 'countries.id')
                ->leftJoin('continents as country_continent', 'countries.continent_id', '=', 'country_continent.id')
                ->leftJoin('continents as publisher_continent', 'publisher.continent_id', '=', 'publisher_continent.id')
                ->leftJoin('languages', 'publisher.language_id', '=', 'languages.id')
                ->leftJoin('buyer_purchased', function($q) use ($user){
                    $q->on('publisher.id', '=', 'buyer_purchased.publisher_id')
                        ->where('buyer_purchased.user_id_buyer', $user->id);
                })
                ->whereNotNull('users.id') // to remove all seller's URL that deleted
                ->where('registration.account_validation', 'valid')
                ->where('publisher.valid', 'valid')
                ->where('publisher.qc_validation', 'yes')
                ->whereNotNull('publisher.href_fetched_at');


        // if( Auth::user()->role_id == 5 || (isset($registered->type) && $registered->type == 'Buyer') ){
        //     $list->where('publisher.valid', 'valid');
        // }

        if( isset($filter['casino_sites']) && !empty($filter['casino_sites']) ){
            $list->where('publisher.casino_sites', $filter['casino_sites']);
        }

        if( isset($filter['seller']) && !empty($filter['seller']) ){
            $list->where('publisher.user_id', $filter['seller']);
        }

        if (isset($filter['is_https'])) {
            $list->where('publisher.is_https', $filter['is_https']);
        }

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list->where('publisher.url', 'like', '%'.$filter['search'].'%');
        }

        if( isset($filter['language_id']) && !empty($filter['language_id']) ){
            if (is_array($filter['language_id'])) {
                $list->whereIn('publisher.language_id', $filter['language_id']);
            } else {
                $list->where('publisher.language_id', $filter['language_id']);
            }
        }

        if( isset($filter['status_purchase']) && !empty($filter['status_purchase']) ){
            if (is_array($filter['status_purchase'])) {
                if (in_array('New', $filter['status_purchase'])) {
                    $list->where(function ($query) use ($filter) {
                        $query->whereNull('buyer_purchased.publisher_id');
                        $query->orWhereIn('buyer_purchased.status', $filter['status_purchase']);
                    });
                } else {
                    $list->whereIn('buyer_purchased.status', $filter['status_purchase']);
                }
            } else {
                if ($filter['status_purchase'] === 'New') {
                    $list->whereNull('buyer_purchased.publisher_id');
                } else {
                    $list->where('buyer_purchased.status', $filter['status_purchase']);
                }
            }
        }

        if (isset($filter['country_id']) && !empty($filter['country_id'])) {
            if (is_array($filter['country_id'])) {
                $list->whereIn('publisher.country_id', $filter['country_id']);
            } else {
                $list->where('publisher.country_id', $filter['country_id']);
            }
        }

        if (isset($filter['continent_id']) && !empty($filter['continent_id'])) {
            if (is_array($filter['continent_id'])) {
                $list = $list->where(function ($query) use ($filter) {
                    if (($key = array_search(0, $filter['continent_id'])) !== false) {
                        unset($filter['continent_id'][$key]);
                        $query->orWhere(function ($subs) {
                            $subs->orWhere('publisher.continent_id', null)
                                ->where('publisher.country_id', null);
                        });
                    }

                    if(!empty($filter['continent_id'])){
                        $query->orWhereIn('countries.continent_id', $filter['continent_id'])
                            ->orWhereIn('publisher.continent_id', $filter['continent_id']);
                    }
                });

            } else {
                $list = $list->where(function ($query) use ($filter) {
                    $query->where('countries.continent_id', $filter['continent_id'])
                        ->orWhere('publisher.continent_id', $filter['continent_id']);
                });
            }
        }

        if (isset($filter['domain_zone']) && !empty($filter['domain_zone'])) {
            if (is_array($filter['domain_zone'])) {

//                $regs = implode("|", $filter['domain_zone']);
                $regs = implode(",", $filter['domain_zone']);
                $regs = str_replace('.', '', $regs);
                $regs = explode(",", $regs);

//                $list = $list->whereRaw("REPLACE(SUBSTRING_INDEX(url, '.', -1),' ','') REGEXP '" . $regs . "'");

                $list = $list->whereIn(DB::raw("REPLACE(REPLACE(SUBSTRING_INDEX(url, '.', -1),' ',''),'/','')"), $regs);
            } else {

                $regs = str_replace('.', '', $filter['domain_zone']);

//                $list = $list->whereRaw("REPLACE(SUBSTRING_INDEX(url, '.', -1),' ','') like '%" . $regs . "%'");
                $list = $list->whereRaw("REPLACE(REPLACE(SUBSTRING_INDEX(url, '.', -1),' ',''),'/','') = '$regs'");
            }
        }

        if (isset($filter['ur']) && !empty($filter['ur'])) {
            if ($filter['ur_direction'] === 'Above') {
                $list->where('publisher.ur' , '>=', intval($filter['ur']));
            } else {
                $list->where('publisher.ur', '<=', intval($filter['ur']));
            }
        }

        if (isset($filter['dr']) && !empty($filter['dr'])) {
            if ($filter['dr_direction'] === 'Above') {
                $list->where('publisher.dr' , '>=', intval($filter['dr']));
            } else {
                $list->where('publisher.dr', '<=', intval($filter['dr']));
            }
        }

        if (isset($filter['org_kw']) && !empty($filter['org_kw'])) {
            if ($filter['org_kw_direction'] === 'Above') {
                $list->where('publisher.org_keywords' , '>=', intval($filter['org_kw']));
            } else {
                $list->where('publisher.org_keywords', '<=', intval($filter['org_kw']));
            }
        }

        if (isset($filter['org_traffic']) && !empty($filter['org_traffic'])) {
            if ($filter['org_traffic_direction'] === 'Above') {
                $list->where('publisher.org_traffic' , '>=', intval($filter['org_traffic']));
            } else {
                $list->where('publisher.org_traffic', '<=', intval($filter['org_traffic']));
            }
        }

        if (isset($filter['price']) && !empty($filter['price'])) {
            if ($filter['price_direction'] === 'Above') {
                $list->where('publisher.price' , '>=', intval($filter['price']));
            } else {
                $list->where('publisher.price', '<=', intval($filter['price']));
            }
        }

        if (isset($filter['price_basis']) && !empty($filter['price_basis'])) {
            if (is_array($filter['price_basis'])) {
                $list->whereIn('publisher.price_basis', $filter['price_basis']);
            } else {
                $list->where('publisher.price_basis', $filter['price_basis']);
            }
        }

        if (isset($filter['code'])) {
            if(is_array($filter['code'])) {
                $list->where(function($q) use ($filter){
                    foreach($filter['code'] as $key => $code) {
                        if($key == 0) {
                            $q->whereRaw('ROUND(
                                (
                                LENGTH(publisher.code_comb)- LENGTH( REPLACE (publisher.code_comb, "A", "") )
                                ) / LENGTH("A")) = ' . rtrim($code, 'A'));
                        } else {
                            $q->orWhere(function($query) use ($code){
                                $query->whereRaw('ROUND(
                                    (
                                    LENGTH(publisher.code_comb)- LENGTH( REPLACE (publisher.code_comb, "A", "") )
                                    ) / LENGTH("A")) = ' . rtrim($code, 'A'));
                            }) ;
                        }
                    }
                });
            } else {
                $list->whereRaw('ROUND(
                    (
                    LENGTH(publisher.code_comb)- LENGTH( REPLACE (publisher.code_comb, "A", "") )
                    ) / LENGTH("A")) = ' . rtrim($filter['code'], 'A'));
            }
        }

        if( isset($filter['topic']) && !empty($filter['topic']) ){
            if(is_array($filter['topic'])) {
                $list->where(function ($query) use ($filter) {
                    $ctr = 0;
                    foreach($filter['topic'] as $topic) {
                        // $list = $list->where('publisher.topic', 'like', '%'.$topic.'%');
                        if($ctr == 0) {
                            $query->where('publisher.topic', 'like', '%'.$topic.'%');
                        } else {
                            $query->orWhere('publisher.topic', 'like', '%'.$topic.'%');
                        }
                        $ctr++;
                    }
                });

            } else {
                $list = $list->where('publisher.topic', 'like', '%'. $filter['topic'].'%');
            }
        }

        if (isset($filter['sort']) && !empty($filter['sort'])) {
            foreach ($filter['sort'] as &$sort) {
                $sort = \GuzzleHttp\json_decode($sort);
                $list = $list->orderByRaw("$sort->column $sort->sort");
            }
        } else {
            $list->orderBy('created_at', 'desc');
        }

        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            $result = $list->orderBy('id', 'desc')->get();
        }else{
            $result = $list->paginate($paginate);
        }

        // Getting credit left
        if ( isset($user->registration->is_sub_account) && $user->registration->is_sub_account == 1 ) {
            if ( isset($user->registration->team_in_charge) ) {
                $user_model = User::where('id', $user->registration->team_in_charge)->first();
                $user->id = isset($user_model->id) ? $user_model->id : $user->id;
            }
        }

        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user->id)->pluck('email');
        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');
        $UserId[] = $user->id;

        $total_purchased = Backlink::selectRaw('SUM(prices) as total_purchased')
            ->where('status', '!=', 'Canceled')
            ->where(function($query) use ($sub_buyer_ids, $UserId){
                if(count($sub_buyer_ids) > 0) {
                    return $query->whereIn('user_id', array_merge($sub_buyer_ids->toArray(),$UserId));
                } else{
                    return $query->whereIn('user_id', $UserId);
                }
            })
            ->get();

        // $total_purchased = Backlink::selectRaw('SUM(prices) as total_purchased')
        //     ->where('user_id', $user->id)
        //     ->when(count($sub_buyer_ids) > 0, function($query) use ($sub_buyer_ids){
        //         return $query->orWhereIn('user_id', $sub_buyer_ids);
        //     })
        //     ->get();


        $wallet_transaction = WalletTransaction::selectRaw('SUM(amount_usd) as amount_usd')
            ->where('user_id', $user->id)
            ->where('admin_confirmation', '!=', 'Not Paid')
            ->get();

        if( isset($wallet_transaction[0]['amount_usd']) ){
            if (isset($total_purchased[0]['total_purchased'])) {
                $credit = floatval($wallet_transaction[0]['amount_usd']) - floatval($total_purchased[0]['total_purchased']);
            } else {
                $credit = floatval($wallet_transaction[0]['amount_usd']);
            }
        }
        // End of Getting credit left

        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            return response()->json([
                'data' => $result,
                'total' => $result->count(),
                'credit' => round($credit),
            ],200);
        }else{
            $custom_credit = collect(['credit' => round($credit)]);
            $result = $custom_credit->merge($result);

            return $result;
        }
    }

    /**
     * Buy function
     *
     * @param Request               $request
     * @param NotificationInterface $notification
     * @return response
     */
    public function update(Request $request, NotificationInterface $notification) {
        $publisher = Publisher::find($request->publisher_id ? $request->publisher_id : $request->id);
        $user = Auth::user();

        if ($user->credit_auth != 'Yes' && $request->credit_left < $request->seller_price) {
            return response()->json([
                'message' => 'Insufficient Credits',
                'errors' => [
                    'insufficent_credits'
                ]
            ], 422);
        }

        $this->updateStatus('Purchased', $publisher->id);

        $backlink = Backlink::updateOrCreate([
            'publisher_id' => $publisher->id,
            'user_id' => $user->id
        ],[
            'prices' => $request->prices,
            'price' => $request->seller_price,
            'url_advertiser' => $request->url_advertiser,
            'anchor_text' => $request->anchor_text,
            'link' => $request->link,
            'publisher_id' => $publisher->id,
            'user_id' => $user->id,
            'status' => 'Pending', // default status when buys a URL
            'date_process' => date('Y-m-d'),
            'ext_domain_id' => 0,
            'int_domain_id' => 0,
            'is_https' => $this->httpClient->getProtocol($request->url_advertiser) == 'https' ? 'yes' : 'no'
        ]);

        event(new BuyEvent($backlink, $user));
        event(new SellerReceivesOrderEvent($backlink, $publisher->user));
        event(new BacklinkStatusChangedEvent($backlink, $backlink->publisher->user));

        if( isset($backlink->publisher->inc_article) &&  strtolower($backlink->publisher->inc_article) == "no"){
            Article::create([
                'id_backlink' => $backlink->id,
                'id_language' => $backlink->publisher->language_id,
            ]);
            $users = User::where('status','active')->where('role_id',4)->get();
            foreach($users as $user)
            {
//                event(new NotificationEvent("New Article to be write today!", $user->id));
            }

        }

        return response()->json(['success'=> true], 200);
    }

    public function updateDislike(Request $request) {
        if( is_array($request->id) ){
            foreach( $request->id as $id ){
                $publisher = Publisher::find($id);
                $this->updateStatus('Not interested', $publisher->id);
            }
        }else{
            $publisher = Publisher::find($request->id);
            $this->updateStatus('Not interested', $publisher->id);
        }

        return response()->json(['success'=> true], 200);
    }

    public function updateLike(Request $request) {
        $user = auth()->user();

        if( is_array($request->id) ){
            foreach( $request->id as $id ){
                $publisher = Publisher::find($id);
                $this->updateStatus('Interested', $publisher->id);

                Backlink::create([
                    'prices' => $request->prices,
                    'price' => $request->seller_price,
                    'url_advertiser' => $request->url_advertiser,
                    'anchor_text' => $request->anchor_text,
                    'link' => $request->link,
                    'publisher_id' => $publisher->id,
                    'user_id' => $user->id,
                    'status' => 'To Be Validated',
                    'date_process' => date('Y-m-d'),
                    'ext_domain_id' => 0,
                    'int_domain_id' => 0,
                    'is_https' => $this->httpClient->getProtocol($request->url_advertiser) == 'https' ? 'yes' : 'no'
                ]);
            }
        }else{
            $publisher = Publisher::find($request->id);
            $this->updateStatus('Interested', $publisher->id);

            Backlink::create([
                'prices' => $request->prices,
                'price' => $request->seller_price,
                'url_advertiser' => $request->url_advertiser,
                'anchor_text' => $request->anchor_text,
                'link' => $request->link,
                'publisher_id' => $publisher->id,
                'user_id' => $user->id,
                'status' => 'To Be Validated',
                'date_process' => date('Y-m-d'),
                'ext_domain_id' => 0,
                'int_domain_id' => 0,
                'is_https' => $this->httpClient->getProtocol($request->url_advertiser) == 'https' ? 'yes' : 'no'
            ]);
        }

        return response()->json(['success'=> true], 200);
    }

    public function UpdateUninterested(Request $request)
    {
        $backlink = Backlink::find($request->backlink_id);

        $buyerPurchasedIds = BuyerPurchased::where([
            'user_id_buyer' => $backlink->user_id,
            'publisher_id' => $request->publisher_id
        ])->get()->pluck('id');

        Backlink::destroy($backlink->id);
        BuyerPurchased::destroy($buyerPurchasedIds);

        return 'OK';
    }

    public function checkCreditAuth() {
        $data = Auth::user()->credit_auth;
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    /**
     *
     * get code combination of list backlinks
     *
     * @param integer $a
     * @param integer $b
     * @param string $type
     *
     * @return string
     */
    private function getCodeCombination($a, $b, $type)
    {
        switch ( $type ) {
            case "value1":
                $score = $b - $a;
                $val = '';
                if( $score < 5 && $score >= -3){  $val = 'A'; }
                else if( $score <= 8 && $score >= 5){ $val = 'C'; }
                else if( $score <= -4 && $score >= -8){ $val = 'D'; }
                else if( $score >= 8 || $score <= -8){ $val = 'E'; }
                return $val;
            case "value2":
                if($a == 0){
                    return '';
                }
                $score = number_format( floatVal(divnum($a, $b)) , 2, '.', '');
                $val = '';
                if( $score >= 1 && $score < 3){  $val = 'A'; }
                else if( $score >= 3 && $score < 8){ $val = 'C'; }
                else if( $score >= 8 && $score < 16){ $val = 'D'; }
                else if( $score >= 16 ){ $val = 'E'; }
                return $val;
            case "value3":
                $val = '';
                if( $a >= 1000){ $val = 'A'; }
                else if( $a >= 500 && $a < 1000){ $val = 'B'; }
                else if( $a >= 100 && $a < 500){ $val = 'C'; }
                else if( $a >= 50 && $a < 100){ $val = 'D'; }
                else if( $a < 50 ){ $val = 'E'; }
                return $val;
            case "value4":
                $val = '';
                if( $a >= 10000){ $val = 'A'; }
                else if( $a >= 5000 && $a < 10000){ $val = 'B'; }
                else if( $a >= 1000 && $a < 5000){ $val = 'C'; }
                else if( $a >= 500 && $a < 1000){ $val = 'D'; }
                else if( $a < 500 ){ $val = 'E'; }
                return $val;
            default:
                return '';
        }
    }

}
