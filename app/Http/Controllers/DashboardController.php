<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use Illuminate\Support\Facades\DB;
use App\Models\Backlink;
use App\Models\ExtDomain;
use Illuminate\Support\Facades\Auth;
use App\Models\BuyerPurchased;

class DashboardController extends Controller
{
    public function index() {
        $total_seller = $this->TotalSeller();
        $incomes = $this->incomes();
        $purchase = $this->purchase();
        $backlink_seller = $this->backlinkSeller();
        $backlink_buyer = $this->backlinkBuyer();
        $ext_domain = $this->extDomain();
        $backlink_to_buy = $this->backlinkToBuy();

        $data = [
            'total_seller' => $total_seller,
            'incomes' => $incomes,
            'purchase' => $purchase,
            'backlink_seller' => $backlink_seller,
            'backlink_buyer' => $backlink_buyer,
            'ext_domain' => $ext_domain,
            'backlink_to_buy' => $backlink_to_buy,
        ];

        return response()->json($data);
    }

    private function TotalSeller() {
        $columns = [
            'users.username',
            DB::raw('COUNT(publisher.url) as num_sites'),
            DB::raw('SUM(CASE WHEN publisher.valid = "unchecked" THEN 1 ELSE 0 END) AS num_unchecked'),
            DB::raw('SUM(CASE WHEN publisher.valid = "valid" THEN 1 ELSE 0 END) AS num_valid'),
            DB::raw('SUM(CASE WHEN publisher.valid = "invalid" THEN 1 ELSE 0 END) AS num_invalid'),
        ];
        $list = Publisher::select($columns)
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

    private function purchase() {
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

    private function backlinkBuyer() {
        $columns = [
            'users.username',
            DB::raw('SUM(CASE WHEN backlinks.status = "Processing" THEN 1 ELSE 0 END) AS num_processing'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Content Writing" THEN 1 ELSE 0 END) AS writing'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Content Done" THEN 1 ELSE 0 END) AS num_done'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Content Sent" THEN 1 ELSE 0 END) AS num_sent'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Live" THEN 1 ELSE 0 END) AS num_live'),
        ];

        $list = Backlink::select($columns)
                    ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
                    ->leftJoin('users', 'backlinks.user_id', '=', 'users.id');

        if( Auth::user()->role_id == 5 && Auth::user()->isOurs == 0 ){
            $list = $list->where('backlinks.user_id', Auth::user()->id);
        }
                    
        return $list->groupBy('backlinks.user_id', 'users.username')
                    ->orderBy('users.username', 'asc')
                    ->get();
    }

    private function extDomain() {
        $columns = [
            'users.username',
            DB::raw('SUM(CASE WHEN ext_domains.status = "70" THEN 1 ELSE 0 END) AS num_in_touched'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "100" THEN 1 ELSE 0 END) AS num_qualified'),
            DB::raw('SUM(CASE WHEN ext_domains.status = "90" THEN 1 ELSE 0 END) AS num_unqualified'),
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

        $publisher = Publisher::count();

        $columns = [
            'users.username',
            DB::raw('SUM(CASE WHEN buyer_purchased.status = "Not interested" THEN 1 ELSE 0 END) AS num_not_interested'),
            DB::raw('SUM(CASE WHEN buyer_purchased.status = "Interested" THEN 1 ELSE 0 END) AS num_interested'),
            DB::raw('SUM(CASE WHEN buyer_purchased.status = "Purchased" THEN 1 ELSE 0 END) AS num_purchased'),
            DB::raw('
                SUM(CASE WHEN buyer_purchased.status = "Purchased" 
                OR buyer_purchased.status = "Not interested" 
                OR buyer_purchased.status = "Interested" THEN 1 ELSE 0 END) AS num_total
            '),
        ];

        $buyer_purchased = BuyerPurchased::select($columns)
                ->leftJoin('users', 'buyer_purchased.user_id_buyer', '=', 'users.id')
                ->leftJoin('publisher', 'buyer_purchased.publisher_id', '=', 'publisher.id')
                ->where('publisher.valid', '=' ,'valid');
                
        if( Auth::user()->role_id == 5 ){
            $buyer_purchased = $buyer_purchased->where('buyer_purchased.user_id_buyer', Auth::user()->id);
        }

        $buyer_purchased = $buyer_purchased->groupBy('users.username')
                ->orderBy('users.username', 'asc')
                ->get();

        $list = [];
        foreach ($buyer_purchased as $purchased){
            $new = intval($publisher) - intval($purchased['num_total']);
            array_push($list, [
                'username' => $purchased['username'],
                'num_new' => $new,
                'num_not_interested' => $purchased['num_not_interested'],
                'num_interested' => $purchased['num_interested'],
                'num_purchased' => $purchased['num_purchased'],
                'num_total' => intval($purchased['num_total']) + intval($new)
            ]);
        }

        return $list;
    }

}
