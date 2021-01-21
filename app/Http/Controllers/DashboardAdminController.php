<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\ExtDomain;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{   
    private $filter = '';

    public function __construct(Request $request) {
        $this->filter = $request->all();
        $range_date = $this->rangeDate();

        // dd($range_date);
    }

    public function index() {
        $url_statistics = $this->urlStatistics();
        $seller_statistics = $this->sellerStatistics();


        return [
            'url_statistics' => $url_statistics,
            'seller_statistics' => $seller_statistics,
        ];
    }


    private function sellerStatistics() {
        $request = $this->filter;
        $range_date = $this->rangeDate();
        $labels = ['New', 'GotContacts', 'Contacted', 'InTouched', 'Qualified', 'NoAnswer', 'Refused', 'Unqualified'];
        $colors = $this->colorList();
        $column = [
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'),
            DB::raw('COUNT(CASE WHEN ext_domains.status = "0" THEN 1 ELSE NULL END) as num_new'),
            DB::raw('COUNT(CASE WHEN ext_domains.status = "30" THEN 1 ELSE NULL END) as num_gotcontacts'),
            DB::raw('COUNT(CASE WHEN ext_domains.status = "50" THEN 1 ELSE NULL END) as num_contacted'),
            DB::raw('COUNT(CASE WHEN ext_domains.status = "70" THEN 1 ELSE NULL END) as num_intouched'),
            DB::raw('COUNT(CASE WHEN ext_domains.status = "100" THEN 1 ELSE NULL END) as num_qualified'),
            DB::raw('COUNT(CASE WHEN ext_domains.status = "55" THEN 1 ELSE NULL END) as num_no_answer'),
            DB::raw('COUNT(CASE WHEN ext_domains.status = "60" THEN 1 ELSE NULL END) as num_refused'),
            DB::raw('COUNT(CASE WHEN ext_domains.status = "90" THEN 1 ELSE NULL END) as num_unqualified'),
        ];

        $datasets = array();
        foreach ($labels as $key => $label) {

            $data_label = [];
            foreach ( $range_date as $key_date => $date ) {
                $extract_date = explode('-',$key_date);
                $date = $extract_date[2] == '15' ? '01':'16';
                $year_month = $extract_date[0].'-'.$extract_date[1];


                $ext_domains = ExtDomain::select($column)
                            ->where('created_at', '>=', $year_month.'-'.$date)
                            ->where('created_at', '<=', $key_date)
                            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
                            ->get();

                $sum_no_new = [];
                $sum_no_got_contacts = [];
                $sum_no_contacted = [];
                $sum_no_intouched = [];
                $sum_no_qualified = [];
                $sum_no_answer = [];
                $sum_no_refused = [];
                $sum_no_unqualified = [];

                if($ext_domains) {
                    foreach ($ext_domains as $ext_domain) {
                        $sum_no_new[] = $ext_domain->num_new;
                        $sum_no_got_contacts[] = $ext_domain->num_gotcontacts;
                        $sum_no_contacted[] = $ext_domain->num_contacted;
                        $sum_no_intouched[] = $ext_domain->num_intouched;
                        $sum_no_qualified[] = $ext_domain->num_qualified;
                        $sum_no_answer[] = $ext_domain->num_no_answer;
                        $sum_no_refused[] = $ext_domain->num_refused;
                        $sum_no_unqualified[] = $ext_domain->num_unqualified;
                    }
                } else {
                    $sum_no_new[] = 0;
                    $sum_no_got_contacts[] = 0;
                    $sum_no_contacted[] = 0;
                    $sum_no_intouched[] = 0;
                    $sum_no_qualified[] = 0;
                    $sum_no_answer[] = 0;
                    $sum_no_refused[] = 0;
                    $sum_no_unqualified[] = 0;
                }
                    
                if( $label == 'New' ) {
                    $data_label[] = array_sum($sum_no_new);
                } 
                else if ($label == 'GotContacts') {
                    $data_label[] = array_sum($sum_no_got_contacts);
                }
                else if ($label == 'Contacted') {
                    $data_label[] = array_sum($sum_no_contacted);
                }
                else if ($label == 'InTouched') {
                    $data_label[] = array_sum($sum_no_intouched);
                }
                else if ($label == 'Qualified') {
                    $data_label[] = array_sum($sum_no_qualified);
                }
                else if ($label == 'NoAnswer') {
                    $data_label[] = array_sum($sum_no_answer);
                }
                else if ($label == 'Refused') {
                    $data_label[] = array_sum($sum_no_refused);
                }
                else if ($label == 'Unqualified') {
                    $data_label[] = array_sum($sum_no_unqualified);
                }

                
            }

            array_push($datasets,[
                "label" => $label,
                "backgroundColor" => "rgba(255,255,255,000.2)",
                "borderColor" => $colors[$key],
                "data" => $data_label,
            ]);
        }

        $datasets_labels = array();
        foreach ( $range_date as $label_name ) {
            $datasets_labels[] = $label_name;
        }


        return [
            "labels" => $datasets_labels,
            "datasets" => $datasets
        ];
    }



    private function urlStatistics() {
        $request = $this->filter;
        $range_date = $this->rangeDate();
        $labels = ['Sites', 'Valid', 'Unchecked', 'Invalid'];
        $colors = $this->colorList();
        $column = [
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'),
            DB::raw('COUNT(CASE WHEN publisher.valid = "valid" THEN 1 ELSE NULL END) as num_valid'),
            DB::raw('COUNT(CASE WHEN publisher.valid = "invalid" THEN 1 ELSE NULL END) as num_invalid'),
            DB::raw('COUNT(CASE WHEN publisher.valid = "unchecked" THEN 1 ELSE NULL END) as num_unchecked'),
            DB::raw('
                COUNT(CASE WHEN publisher.valid = "valid" 
                OR publisher.valid = "invalid" 
                OR publisher.valid = "unchecked"
                THEN 1 ELSE NULL END) AS num_sites
            '),
        ];

        $datasets = array();
        foreach ($labels as $key => $label) {

            $data_label = [];
            foreach ( $range_date as $key_date => $date ) {
                $extract_date = explode('-',$key_date);
                $date = $extract_date[2] == '15' ? '01':'16';
                $year_month = $extract_date[0].'-'.$extract_date[1];


                $publishers = Publisher::select($column)
                            ->where('created_at', '>=', $year_month.'-'.$date)
                            ->where('created_at', '<=', $key_date)
                            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
                            ->get();

                $sum_no_sites = [];
                $sum_no_valid = [];
                $sum_no_invalid = [];
                $sum_no_unchecked = [];
                if($publishers) {
                    foreach ($publishers as $publisher) {
                        $sum_no_sites[] = $publisher->num_sites;
                        $sum_no_valid[] = $publisher->num_valid;
                        $sum_no_invalid[] = $publisher->num_invalid;
                        $sum_no_unchecked[] = $publisher->num_unchecked;
                    }
                } else {
                    $sum_no_sites[] = 0;
                    $sum_no_valid[] = 0;
                    $sum_no_invalid[] = 0;
                    $sum_no_unchecked[] = 0;
                }
                    
                if( $label == 'Sites' ) {
                    $data_label[] = array_sum($sum_no_sites);
                } 
                else if ($label == 'Valid') {
                    $data_label[] = array_sum($sum_no_valid);
                }
                else if ($label == 'Unchecked') {
                    $data_label[] = array_sum($sum_no_unchecked);
                }
                else if ($label == 'Invalid') {
                    $data_label[] = array_sum($sum_no_invalid);
                }

                
            }

            array_push($datasets,[
                "label" => $label,
                "backgroundColor" => "rgba(255,255,255,000.2)",
                "borderColor" => $colors[$key],
                "data" => $data_label,
            ]);
        }

        $datasets_labels = array();
        foreach ( $range_date as $label_name ) {
            $datasets_labels[] = $label_name;
        }


        return [
            "labels" => $datasets_labels,
            "datasets" => $datasets
        ];
    }

    private function colorList() {
        $list = [
            '#ed13d7',
            '#ff5454',
            '#3530d1',
            '#54a851',
            '#0cdfeb',
            '#f6ff47',
            '#f0752e',
            '#7900e3',
            '#efefef',
        ];
        return $list;
    }

    private function rangeDate() {
        $request = $this->filter;
        $range_month = 4;
        $result = [];
        $result2 = [];
        $result3 = [];
        $sub_3_months = date("01-m-Y", strtotime("-2 months"));
        $time = strtotime($sub_3_months);
        for($i = 1;$i < $range_month;$i++) {
            $result[date("Y-m-15", $time)] = date("15 M", $time);
            $result[date("Y-m-t", $time)] = date("t M", $time);
            $time = strtotime("+1 month", $time);
        }


        if($request['is_filter'] == true) {

            // Monthly filter
            if($request['date_type'] == 'Monthly'){
                if($request['monthly'] != null){
                    $lastday_of_the_month = date('t', strtotime(date("Y").'-'.$request['monthly']));
                    for($i = 1;$i <= $lastday_of_the_month;$i++){
                        $date = date('Y').'-'.$request['monthly'].'-'.$i;
                        $result2[date('Y-m-d',strtotime($date))] = date('d M',strtotime($date));
                    }

                    return $result2;
                }
            }
                
            // Range Date filter
            if($request['date_type'] == 'Monthly'){
                if($request['date1'] != null && $request['date2'] != null){
                    return $result3;
                }
            }
        }

        return $result;
    }
}
