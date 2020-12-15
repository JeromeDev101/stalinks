<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use Illuminate\Support\Facades\DB;
use App\Models\Backlink;
use App\Models\ExtDomain;
use Illuminate\Support\Facades\Auth;
use App\Models\BuyerPurchased;
use App\Models\User;
use App\Models\Registration;

class DashboardController extends Controller
{
    public function index() {
        $total_seller = $this->TotalSeller();
        $incomes = $this->incomes();
        $purchase = $this->purchase();
        $backlink_seller = $this->backlinkSeller();
        $backlink_buyer = $this->backlinkBuyer('');
        $ext_domain = $this->extDomain();
        $backlink_to_buy = $this->backlinkToBuy();
        $inCharge = $this->inCharge();

        $data = [
            'total_seller' => $total_seller,
            'incomes' => $incomes,
            'purchase' => $purchase,
            'backlink_seller' => $backlink_seller,
            'backlink_buyer' => $backlink_buyer,
            'ext_domain' => $ext_domain,
            'backlink_to_buy' => $backlink_to_buy,
            'team_in_charge'=> $inCharge,
        ];

        return response()->json($data);
    }

    private function inCharge(){
        $seller_in_charge = User::select('id','username')->where('isOurs', 0)->where('role_id',6)->get();
        $data = [];
        foreach($seller_in_charge as $in_charge){
            $reg_seller = Registration::where('team_in_charge', $in_charge['id'])->count();

            array_push($data,[
                'username' => $in_charge['username'],
                'total_seller' => $reg_seller
            ]);
        }

        return $data;
    }

    private function TotalSeller() {
        $columns = [
            'A.username as username',
            'B.username as in_charge',
            DB::raw('COUNT(publisher.url) as num_sites'),
            DB::raw('SUM(CASE WHEN publisher.valid = "unchecked" THEN 1 ELSE 0 END) AS num_unchecked'),
            DB::raw('SUM(CASE WHEN publisher.valid = "valid" THEN 1 ELSE 0 END) AS num_valid'),
            DB::raw('SUM(CASE WHEN publisher.valid = "invalid" THEN 1 ELSE 0 END) AS num_invalid'),
        ];
        $list = Publisher::select($columns)
                    ->leftJoin('users as A', 'publisher.user_id', '=', 'A.id')
                    ->leftJoin('registration', 'A.email', '=', 'registration.email')
                    ->leftJoin('users as B', 'registration.team_in_charge', '=', 'B.id');
                    
  
        if( Auth::user()->role_id == 6 && Auth::user()->isOurs == 1 ){
            $list = $list->where('A.id', Auth::user()->id);
        }

        if( Auth::user()->role_id == 6 && Auth::user()->isOurs == 0 ){
            $list = $list->where('registration.team_in_charge', Auth::user()->id);
        }

        return $list->groupBy('publisher.user_id', 'A.username', 'B.username')
                    ->orderBy('A.username', 'asc')
                    ->get();
    }

    private function incomes() {
        $columns = [
            'users.username',
            DB::raw('COUNT(publisher.url) as num_backlink'),
            DB::raw('SUM(CASE WHEN backlinks.payment_status = "Not Paid" THEN 1 ELSE 0 END) AS num_unpaid'),
            DB::raw('SUM(CASE WHEN backlinks.payment_status = "Paid" THEN 1 ELSE 0 END) AS num_paid'),
        ];

        $list = Backlink::select($columns)
                    ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
                    ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                    ->leftJoin('registration', 'users.email', '=', 'registration.email')
                    ->where('backlinks.status', 'Live');

        if( Auth::user()->role_id == 6 && Auth::user()->isOurs == 1 ){
            $list = $list->where('users.id', Auth::user()->id);
        }

        if( Auth::user()->role_id == 6 && Auth::user()->isOurs == 0 ){
            $list = $list->where('registration.team_in_charge', Auth::user()->id);
        }

        return $list->groupBy('publisher.user_id', 'users.username')
                    ->orderBy('users.username', 'asc')
                    ->get();
    }

    private function purchase() {
        $user_id = Auth::user()->id;
        $registered = Registration::where('email', Auth::user()->email)->first();
        if ( isset($registered->is_sub_account) && $registered->is_sub_account == 1 ) {
            if ( isset($registered->team_in_charge) ) {
                $user_model = User::where('id', $registered->team_in_charge)->first();
                $user_id = isset($user_model->id) ? $user_model->id : Auth::user()->id;
            }
        }

        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user_id)->pluck('email');
        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');

        $columns = [
            'users.username',
            DB::raw('COUNT(publisher.url) as num_backlink'),
            DB::raw('SUM(CASE WHEN backlinks.payment_status = "Not Paid" THEN 1 ELSE 0 END) AS num_unpaid'),
            DB::raw('SUM(CASE WHEN backlinks.payment_status = "Paid" THEN 1 ELSE 0 END) AS num_paid'),
        ];

        $list = Backlink::select($columns)
                    ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
                    ->leftJoin('users', 'backlinks.user_id', '=', 'users.id')
                    ->where('backlinks.status', 'Live');

        if( Auth::user()->role_id == 5 && Auth::user()->isOurs == 0 ){
            $list = $list->where('backlinks.user_id', Auth::user()->id);
        }

        if( Auth::user()->isOurs == 1 && Auth::user()->role_id == 5 ) {
            $list = $list->where('backlinks.user_id', Auth::user()->id)
                        ->orWhereIn('backlinks.user_id', $sub_buyer_ids);
        }
            
        return $list->groupBy('backlinks.user_id', 'users.username')
                    ->orderBy('users.username', 'asc')
                    ->get();
    }

    private function backlinkSeller() {
        $columns = [
            'users.username',
            DB::raw('SUM(CASE WHEN backlinks.status = "Processing" THEN 1 ELSE 0 END) AS num_processing'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Content Writing" THEN 1 ELSE 0 END) AS writing'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Content Done" THEN 1 ELSE 0 END) AS num_done'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Content Sent" THEN 1 ELSE 0 END) AS num_sent'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Live" THEN 1 ELSE 0 END) AS num_live'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Canceled" THEN 1 ELSE 0 END) AS num_canceled'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Issue" THEN 1 ELSE 0 END) AS num_issue'),
            DB::raw('
                SUM(CASE WHEN backlinks.status = "Live" 
                OR backlinks.status = "Content Sent" 
                OR backlinks.status = "Content Done" 
                OR backlinks.status = "Content Writing" 
                OR backlinks.status = "Processing"  
                OR backlinks.status = "Canceled" 
                OR backlinks.status = "Issue" 
                THEN 1 ELSE 0 END) AS num_total
            '),
        ];

        $list = Backlink::select($columns)
                    ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
                    ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                    ->leftJoin('registration', 'users.email', '=', 'registration.email');

        if( Auth::user()->role_id == 6 && Auth::user()->isOurs == 1 ){
            $list = $list->where('users.id', Auth::user()->id);
        }

        if( Auth::user()->role_id == 6 && Auth::user()->isOurs == 0 ){
            $list = $list->where('registration.team_in_charge', Auth::user()->id);
        }

        return $list->groupBy('publisher.user_id', 'users.username')
                    ->orderBy('users.username', 'asc')
                    ->get();
    }

    private function backlinkBuyer($buyer) {
        $user_id = Auth::user()->id;
        $registered = Registration::where('email', Auth::user()->email)->first();
        if ( isset($registered->is_sub_account) && $registered->is_sub_account == 1 ) {
            if ( isset($registered->team_in_charge) ) {
                $user_model = User::where('id', $registered->team_in_charge)->first();
                $user_id = isset($user_model->id) ? $user_model->id : Auth::user()->id;
            }
        }

        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user_id)->pluck('email');
        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');


        $columns = [
            'users.username',
            DB::raw('SUM(CASE WHEN backlinks.status = "Processing" THEN 1 ELSE 0 END) AS num_processing'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Content In Writing" THEN 1 ELSE 0 END) AS writing'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Content Done" THEN 1 ELSE 0 END) AS num_done'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Content Sent" THEN 1 ELSE 0 END) AS num_sent'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Live" THEN 1 ELSE 0 END) AS num_live'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Canceled" THEN 1 ELSE 0 END) AS num_canceled'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Issue" THEN 1 ELSE 0 END) AS num_issue'),
            DB::raw('
                SUM(CASE WHEN backlinks.status = "Live" 
                OR backlinks.status = "Processing" 
                OR backlinks.status = "Content In Writing" 
                OR backlinks.status = "Content Done" 
                OR backlinks.status = "Content Sent" 
                OR backlinks.status = "Canceled" 
                OR backlinks.status = "Issue" 
                THEN 1 ELSE 0 END) AS num_total
            '),
        ];


        $list = Backlink::select($columns)
                    ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
                    ->leftJoin('users', 'backlinks.user_id', '=', 'users.id');

        if( $buyer != "" ){
            $list = $list->where('backlinks.user_id', $buyer);
        }

        if( Auth::user()->role_id == 5 && Auth::user()->isOurs == 0 ){
            $list = $list->where('backlinks.user_id', Auth::user()->id);
        }

        if( Auth::user()->isOurs == 1 && Auth::user()->role_id == 5 ) {
            $list = $list->where('backlinks.user_id', Auth::user()->id)
                        ->orWhereIn('backlinks.user_id', $sub_buyer_ids);
        }
                    
        return $list->groupBy('backlinks.user_id', 'users.username')
                    ->orderBy('users.username', 'asc')
                    ->get();
    }

    private function extDomain() {
        $columns = [
            'users.username',
            DB::raw('SUM(CASE WHEN ext_domains.status = "0" THEN 1 ELSE 0 END) AS num_new'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "10" THEN 1 ELSE 0 END) AS num_crawl_failed'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "20" THEN 1 ELSE 0 END) AS num_contack_null'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "30" THEN 1 ELSE 0 END) AS num_got_contact'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "40" THEN 1 ELSE 0 END) AS num_ahref'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "50" THEN 1 ELSE 0 END) AS num_contacted'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "55" THEN 1 ELSE 0 END) AS num_no_answer'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "60" THEN 1 ELSE 0 END) AS num_refused'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "70" THEN 1 ELSE 0 END) AS num_in_touched'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "100" THEN 1 ELSE 0 END) AS num_qualified'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "90" THEN 1 ELSE 0 END) AS num_unqualified'),
            DB::raw('
                SUM(CASE WHEN ext_domains.status = "90" 
                OR ext_domains.status = "10" 
                OR ext_domains.status = "20" 
                OR ext_domains.status = "30" 
                OR ext_domains.status = "40" 
                OR ext_domains.status = "50" 
                OR ext_domains.status = "55" 
                OR ext_domains.status = "60" 
                OR ext_domains.status = "70" 
                OR ext_domains.status = "100" 
                OR ext_domains.status = "0" 
                THEN 1 ELSE 0 END) AS num_total
            '),
        ];

        $list = ExtDomain::select($columns)
                ->leftJoin('users', 'ext_domains.user_id', '=', 'users.id')
                ->whereNotNull('user_id')
                ->groupBy('users.username')
                ->orderBy('users.username', 'asc')
                ->get();

        return $list;
    }


    private function backlinkToBuy() {

        $publisher = Publisher::where('valid','valid')
                        ->where('publisher.ur', '!=', '0')
                        ->where('publisher.dr', '!=', '0')
                        ->count();

        $columns = [
            'users.id as id_user',
            'users.username',
            DB::raw('SUM(CASE WHEN buyer_purchased.status = "Not interested" THEN 1 ELSE 0 END) AS num_not_interested'),
            DB::raw('SUM(CASE WHEN buyer_purchased.status = "Interested" THEN 1 ELSE 0 END) AS num_interested'),
            DB::raw('
                SUM(CASE WHEN buyer_purchased.status = "Not interested" 
                OR buyer_purchased.status = "Interested" THEN 1 ELSE 0 END) AS num_total
            '),
        ];

        $buyer_purchased = BuyerPurchased::select($columns)
                ->leftJoin('users', 'buyer_purchased.user_id_buyer', '=', 'users.id')
                ->leftJoin('publisher', 'buyer_purchased.publisher_id', '=', 'publisher.id')
                ->leftJoin('backlinks', 'publisher.id', '=', 'backlinks.publisher_id')
                ->whereNull('backlinks.deleted_at')
                ->whereNull('publisher.deleted_at')
                ->where('publisher.valid', '=' ,'valid')
                ->where('publisher.ur', '!=', '0')
                ->where('publisher.dr', '!=', '0');

                
        if( Auth::user()->role_id == 5 ){
            $buyer_purchased = $buyer_purchased->where('buyer_purchased.user_id_buyer', Auth::user()->id);
        }

        $buyer_purchased = $buyer_purchased->groupBy('users.username', 'users.id')
                ->orderBy('users.username', 'asc')
                ->get();

        $list = [];
        foreach ($buyer_purchased as $purchased){
            $new = intval($publisher) - intval($purchased['num_total']);
            $purchase = $this->backlinkBuyer($purchased['id_user']);

            array_push($list, [
                'username' => $purchased['username'],
                'num_new' => $new,
                'num_not_interested' => $purchased['num_not_interested'],
                'num_interested' => $purchased['num_interested'],
                'num_purchased' => isset($purchase[0]->num_total) ? $purchase[0]->num_total : 0,
                'num_total' => intval($purchased['num_total']) + intval($new)
            ]);
        }

        return $list;
    }

}
