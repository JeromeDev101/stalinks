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

class BuyController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $user_id = Auth::user()->id;

        $columns = [
            'publisher.*',
            'registration.username',
            'users.name',
            'users.username as user_name',
            'users.isOurs',
            'registration.company_name',
            'countries.name AS country_name',
            'buyer_purchased.status as status_purchased'
        ];

        $list = Publisher::select($columns)
                ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                ->leftJoin('registration', 'users.email', '=', 'registration.email')
                ->leftJoin('countries', 'publisher.language_id', '=', 'countries.id')
                ->leftJoin('buyer_purchased', function($q) use ($user_id){
                    $q->on('publisher.id', '=', 'buyer_purchased.publisher_id')
                        ->where('buyer_purchased.user_id_buyer', $user_id);
                });

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list->where('registration.company_name', 'like', '%'.$filter['search'].'%')
                    ->orWhere('users.username', 'like', '%'.$filter['search'].'%');
        }

        if( isset($filter['status_purchase']) && !empty($filter['status_purchase']) ){
            if( $filter['status_purchase'] == 'New' ){
                $list->whereNull('buyer_purchased.publisher_id');
            }else{
                $list->where('buyer_purchased.status', $filter['status_purchase']);
            }
        }

        // if( isset($filter['status_purchase']) && !empty($filter['status_purchase']) ){
        //     if( is_array($filter['status_purchase']) ){
        //         $list->whereIn('buyer_purchased.status', $filter['status_purchase']);

        //         if( in_array('New', $filter['status_purchase']) ){
        //             $list->orWhereNull('buyer_purchased.publisher_id');
        //         }
        //     }else{
        //         $list->where('buyer_purchased.status', $filter['status_purchase']);
        //     }
        // }

        if( isset($filter['language_id']) && !empty($filter['language_id']) ){
            $list->where('publisher.language_id', $filter['language_id']);
        }

        $result = $list->orderBy('publisher.id', 'desc')->get();

        foreach($result as $buy) {
            $codeCombiURDR = $this->getCodeCombination($buy->ur, $buy->dr, 'value1');
            $codeCombiBlRD = $this->getCodeCombination($buy->backlinks, $buy->ref_domain, 'value2');
            $codeCombiOrgKW = $this->getCodeCombination($buy->org_keywords, 0, 'value3');
            $codeCombiOrgT = $this->getCodeCombination($buy->org_traffic, 0, 'value4');
            $combineALl = $codeCombiURDR. $codeCombiBlRD .$codeCombiOrgKW. $codeCombiOrgT;

            $price_list = Pricelist::where('code', strtoupper($combineALl))->first();

            $buy['code_combination'] = $combineALl;
            $buy['code_price'] = $price_list['price'];
        }

        return response()->json([
            'data' => $result
        ],200);
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
        $publisher = Publisher::find($request->id);
        $this->updateStatus($request->id, 'Not interested', $publisher->id);

        return response()->json(['success'=> true], 200);
    }

    public function updateLike(Request $request) {
        $publisher = Publisher::find($request->id);
        $this->updateStatus($request->id, 'Interested', $publisher->id);

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
