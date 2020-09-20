<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use Illuminate\Support\Facades\DB;
use App\Models\Backlink;
use App\Models\ExtDomain;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $total_seller = $this->TotalSeller();
        $incomes = $this->incomes();
        $purchase = $this->purchase();
        $backlink_seller = $this->backlinkSeller();
        $backlink_buyer = $this->backlinkBuyer();
        $ext_domain = $this->extDomain();

        $data = [
            'total_seller' => $total_seller,
            'incomes' => $incomes,
            'purchase' => $purchase,
            'backlink_seller' => $backlink_seller,
            'backlink_buyer' => $backlink_buyer,
            'ext_domain' => $ext_domain,
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
                    ->leftJoin('users', 'publisher.user_id', '=', 'users.id');
                    
  
        if( Auth::user()->role_id == 6 && Auth::user()->isOurs == 1 ){
            $list = $list->where('users.id', Auth::user()->id);
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
                    ->leftJoin('users', 'publisher.user_id', '=', 'users.id');

        if( Auth::user()->role_id == 6 && Auth::user()->isOurs == 1 ){
            $list = $list->where('users.id', Auth::user()->id);
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
                    ->where('backlinks.status', 'Live')
                    ->groupBy('backlinks.user_id', 'users.username')
                    ->orderBy('users.username', 'asc')
                    ->get();

        return $list;
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
                    ->leftJoin('users', 'publisher.user_id', '=', 'users.id');


        if( Auth::user()->role_id == 6 && Auth::user()->isOurs == 1 ){
            $list = $list->where('users.id', Auth::user()->id);
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
                    ->leftJoin('users', 'backlinks.user_id', '=', 'users.id')
                    ->groupBy('backlinks.user_id', 'users.username')
                    ->orderBy('users.username', 'asc')
                    ->get();

        return $list;
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


}
