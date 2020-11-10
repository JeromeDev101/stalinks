<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Publisher;
use App\Models\Backlink;
use App\Models\Pricelist;
use App\Models\BuyerPurchased;
use Illuminate\Cache\RetrievesMultipleKeys;
use Illuminate\Support\Facades\Auth;
use App\Models\Article;
use App\Models\Registration;
use App\Models\WalletTransaction;

class BuyController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $paginate = (isset($filter['paginate']) && !empty($filter['paginate']) ) ? $filter['paginate']:50;
        $user_id = Auth::user()->id;
        $credit = 0;

        $columns = [
            'publisher.*',
            'registration.username',
            'users.name',
            'users.username as user_name',
            'users.isOurs',
            'registration.company_name',
            'countries.name AS country_name',
            'languages.name AS language_name',
            'buyer_purchased.status as status_purchased'
        ];

        $list = Publisher::select($columns)
                ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                ->leftJoin('registration', 'users.email', '=', 'registration.email')
                ->leftJoin('countries', 'publisher.country_id', '=', 'countries.id')
                ->leftJoin('languages', 'publisher.language_id', '=', 'languages.id')
                ->leftJoin('buyer_purchased', function($q) use ($user_id){
                    $q->on('publisher.id', '=', 'buyer_purchased.publisher_id')
                        ->where('buyer_purchased.user_id_buyer', $user_id);
                });

        $registered = Registration::where('email', Auth::user()->email)->first();

        if( Auth::user()->role_id == 5 || (isset($registered->type) && $registered->type == 'Buyer') ){
            $list->where('publisher.valid', 'valid');
        }

        if( isset($filter['seller']) && !empty($filter['seller']) ){
            $list->where('publisher.user_id', $filter['seller']);
        }

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list->where('publisher.url', 'like', '%'.$filter['search'].'%');
        }

        if( isset($filter['language_id']) && !empty($filter['language_id']) ){
            $list->where('publisher.language_id', $filter['language_id']);
        }

        if( isset($filter['status_purchase']) && !empty($filter['status_purchase']) ){
            if( $filter['status_purchase'] == 'New' ){
                $list->whereNull('buyer_purchased.publisher_id');
            }else{
                $list->where('buyer_purchased.status', $filter['status_purchase']);
            }
        }

        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            $result = $list->orderBy('id', 'desc')->get();
        }else{
            $result = $list->paginate($paginate);
        }

        // Getting credit left 

        $total_purchased = Backlink::selectRaw('SUM(price) as total_purchased')
                                ->where('user_id', $user_id)
                                ->get();


        $wallet_transaction = WalletTransaction::selectRaw('SUM(amount_usd) as amount_usd')
                    ->where('user_id', $user_id)
                    ->get();

        if( isset($wallet_transaction[0]['amount_usd']) && isset($total_purchased[0]['total_purchased']) ){
            $credit = floatval($wallet_transaction[0]['amount_usd']) - floatval($total_purchased[0]['total_purchased']);
        }

        // End of Getting credit left 

        
        foreach($result as $key => $value) {

            $codeCombiURDR = $this->getCodeCombination($value->ur, $value->dr, 'value1');
            $codeCombiBlRD = $this->getCodeCombination($value->backlinks, $value->ref_domain, 'value2');
            $codeCombiOrgKW = $this->getCodeCombination($value->org_keywords, 0, 'value3');
            $codeCombiOrgT = $this->getCodeCombination($value->org_traffic, 0, 'value4');
            $combineALl = $codeCombiURDR. $codeCombiBlRD .$codeCombiOrgKW. $codeCombiOrgT;

            $price_list = Pricelist::where('code', strtoupper($combineALl))->first();

            $count_letter_a = substr_count($combineALl, 'A');

            // Filtering of Code A's 
            if( isset($filter['code']) && !empty($filter['code']) ){
                $code = substr($filter['code'],0,1);

                if( $code == $count_letter_a ){
                    $value['code_combination'] = $combineALl;
                    $value['code_price'] = $price_list['price'];
                }else{
                    $result->forget($key);
                }
                
            }else{
                $value['code_combination'] = $combineALl;
                $value['code_price'] = ( isset($price_list['price']) && !empty($price_list['price']) ) ? $price_list['price']:0;
            }

            // Price Basis
            $result_1 = 0;
            $result_2 = 0;

            $price_basis = '-';
            if( !empty($value['code_price']) ){
                
                $var_a = floatVal($value->price);
                $var_b = floatVal($value['code_price']);

                $result_1 = number_format($var_b * 0.7,2);
                $result_2 = number_format( ($var_b * 0.1) + $var_b, 2);

                if( $result_1 != 0 && $result_2 != 0 ){
                    if( $var_a <= $result_1 ){
                        $price_basis = 'Low';
                    }

                    if( $var_a > $result_1 && $result_1 < $result_2 ){
                        $price_basis = 'Good';
                    }

                    if( $var_a > $result_2 ){
                        $price_basis = 'High';
                    }
                }
            }

            if( $value['code_price'] == 0 && $value->price > 0){
                $price_basis = 'High';
            }


            // Filtering of Price Basis
            if( isset($filter['price_basis']) && !empty($filter['price_basis']) ){
                if( $filter['price_basis'] == $price_basis ){
                    $value['price_basis'] = $price_basis;
                }else{
                    $result->forget($key);
                }
            }else{
                $value['price_basis'] = $price_basis;
            }

        }

        
        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            return response()->json([
                'data' => $result,
                'total' => $result->count(),
                'credit' => $credit,
            ],200);
        }else{
            $custom_credit = collect(['credit' => $credit]);
            $result = $custom_credit->merge($result);
            // dd($result);
            
            return $result;
        }
        
    }

    /**
     * Buy function
     *
     * @param Request $request
     * @return response
     */
    public function update(Request $request) {
        $publisher = Publisher::find($request->id);
        $user = Auth::user();

        $this->updateStatus($request->id, 'Purchased', $publisher->id);

        $backlink = Backlink::create([
            'price' => $request->price,
            'url_advertiser' => $request->url_advertiser,
            'anchor_text' => $request->anchor_text,
            'link' => $request->link,
            'publisher_id' => $publisher->id,
            'user_id' => $user->id,
            'status' => 'Processing',
            'date_process' => date('Y-m-d'),
            'ext_domain_id' => 0,
            'int_domain_id' => 0,
        ]);

        if( isset($backlink->publisher->inc_article) &&  $backlink->publisher->inc_article == "No"){
            Article::create([
                'id_backlink' => $backlink->id,
                'id_language' => $backlink->publisher->language_id,
            ]);
        }

        return response()->json(['success'=> true], 200);
    }

    public function updateDislike(Request $request) {
        if( is_array($request->id) ){
            foreach( $request->id as $id ){
                $publisher = Publisher::find($id);
                $this->updateStatus($id, 'Not interested', $publisher->id);
            }
        }else{
            $publisher = Publisher::find($request->id);
            $this->updateStatus($request->id, 'Not interested', $publisher->id);
        }

        return response()->json(['success'=> true], 200);
    }

    public function updateLike(Request $request) {

        if( is_array($request->id) ){
            foreach( $request->id as $id ){
                $publisher = Publisher::find($id);
                $this->updateStatus($id, 'Interested', $publisher->id);
            }
        }else{
            $publisher = Publisher::find($request->id);
            $this->updateStatus($request->id, 'Interested', $publisher->id);
        }

        return response()->json(['success'=> true], 200);
    }

    private function updateStatus($id, $status, $id_publisher) {
        $user = Auth::user();
        $buyer_purchased = BuyerPurchased::where('publisher_id',$id)->where('user_id_buyer', $user->id)->first();

        if( !$buyer_purchased ){
            BuyerPurchased::create([
                'user_id_buyer' => $user->id,
                'publisher_id' => $id_publisher,
                'status' => $status,
            ]);
        }else{
            $buyer_purchased->update(['status' => $status]);
        }
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
                $score = number_format( floatVal($a / $b) , 2, '.', '');
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
