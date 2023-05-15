<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GenerateList;
use App\Models\Pricelist;
use App\Libs\Ahref;
use Illuminate\Support\Facades\Gate;

class GenerateListController extends Controller
{
    public function getList(Request $request) {
        $filter = $request->all();
        $paginate = (isset($filter['paginate']) && !empty($filter['paginate']) ) ? $filter['paginate']:50;


        $generate_list = GenerateList::when(isset($filter['url']) && !empty($filter['url']), function($query) use ($filter) {
                                        return $query->where('url', 'like', '%'.$filter['url'].'%');
                                    });



        if (isset($filter['ur']) && !empty($filter['ur'])) {
            if ($filter['ur_direction'] === 'Above') {
                $generate_list->where('ur' , '>=', intval($filter['ur']));
            } else {
                $generate_list->where('ur', '<=', intval($filter['ur']));
            }
        }

        if (isset($filter['dr']) && !empty($filter['dr'])) {
            if ($filter['dr_direction'] === 'Above') {
                $generate_list->where('dr' , '>=', intval($filter['dr']));
            } else {
                $generate_list->where('dr', '<=', intval($filter['dr']));
            }
        }

        if (isset($filter['org_kw']) && !empty($filter['org_kw'])) {
            if ($filter['org_kw_direction'] === 'Above') {
                $generate_list->where('org_keywords' , '>=', intval($filter['org_kw']));
            } else {
                $generate_list->where('org_keywords', '<=', intval($filter['org_kw']));
            }
        }

        if (isset($filter['org_traffic']) && !empty($filter['org_traffic'])) {
            if ($filter['org_traffic_direction'] === 'Above') {
                $generate_list->where('org_traffic' , '>=', intval($filter['org_traffic']));
            } else {
                $generate_list->where('org_traffic', '<=', intval($filter['org_traffic']));
            }
        }

        if (isset($filter['code'])) {
            if(is_array($filter['code'])) {
                $generate_list->where(function($q) use ($filter){
                    foreach($filter['code'] as $key => $code) {
                        if($key == 0) {
                            $q->whereRaw('ROUND(
                                (
                                LENGTH(code_comb)- LENGTH( REPLACE (code_comb, "A", "") )
                                ) / LENGTH("A")) = ' . rtrim($code, 'A'));
                        } else {
                            $q->orWhere(function($query) use ($code){
                                $query->whereRaw('ROUND(
                                    (
                                    LENGTH(code_comb)- LENGTH( REPLACE (code_comb, "A", "") )
                                    ) / LENGTH("A")) = ' . rtrim($code, 'A'));
                            }) ;
                        }
                    }
                });
            } else {
                $generate_list->whereRaw('ROUND(
                    (
                    LENGTH(code_comb)- LENGTH( REPLACE (code_comb, "A", "") )
                    ) / LENGTH("A")) = ' . rtrim($filter['code'], 'A'));
            }
        }


//        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
//            $result = $generate_list->orderBy('id', 'desc')->get();
//        }else{
//            $result = $generate_list->paginate($paginate);
//        }

        if(isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            return response()->json([
                'data' => $generate_list->get(),
                'total' => $generate_list->count(),
            ],200);
        } else {
            return $generate_list->paginate($paginate);
        }
    }

    public function importCsv(Request $request) {
        if (Gate::denies('upload-generate-list-generate-list')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $request->validate([
            'file' => 'bail|required|mimes:csv,txt',
        ]);

        $csv_file = $request->file;

        $result = true;
        $message = '';
        $file_message = '';

        $csv = fopen($csv_file, 'r');
        $ctr = 0;

        while ( ($line = fgetcsv($csv) ) !== FALSE) {

            if(count($line) > 1 || count($line) < 1){
                $message = "Please check the header: Url";
                $file_message = "Invalid Header format. ".$message;
                $result = false;
                break;
            }

            if( $ctr > 0 ){
                $url = trim_special_characters($line[0]);

                if( trim($url, " ") != '' ){
                    $url_remove_http = remove_http($url);

                    GenerateList::create([
                        'url' => $url_remove_http,
                        'ur' => 0,
                        'dr' => 0,
                        'backlinks' => 0,
                        'ref_domain' => 0,
                        'org_kw' => 0,
                        'org_traffic' => 0,
                        'price' => 0,
                        'code_1' => 'E',
                        'code_2' => 'E',
                        'code_3' => 'E',
                        'code_4' => 'E',
                        'code_comb' => 'EEEE',
                        'price' => 0,
                    ]);
                }
            }

            $ctr++;
        }

        fclose($csv);

        return [
            "success" => $result,
            "message" => $message,
            "errors" => [
                "file" => $file_message,
            ]
        ];

    }

    public function deleteGenerateList(Request $request) {
        if (Gate::denies('delete-generate-list-generate-list')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        if(is_array($request->ids)) {
            foreach($request->ids as $id) {
                $generate_list = GenerateList::find($id);
                $generate_list->delete();
            }
        }
    }

    public function store(Request $request) {
        if (Gate::denies('create-generate-list-generate-list')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $request->validate(['url' => 'required']);
        $url = remove_http($request->url);

        $generate_list = GenerateList::where('url', 'like', '%'.$url.'%')->count();

        if($generate_list > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Existing record'
            ],200);
        }

        GenerateList::create([
            'url' => $url,
            'ur' => 0,
            'dr' => 0,
            'backlinks' => 0,
            'ref_domain' => 0,
            'org_kw' => 0,
            'org_traffic' => 0,
            'price' => 0,
            'code_1' => 'E',
            'code_2' => 'E',
            'code_3' => 'E',
            'code_4' => 'E',
            'code_comb' => 'EEEE',
            'price' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Success'
        ],200);

    }

    public function getAhrefs(Request $request) {
        $generate_list = GenerateList::whereIn('id', $request->ids)->get();

        if ($generate_list->count() == 0) {
            return [];
        }

        $configs['token'] = '1221d525cb70af4ee61e2561ced8985935920451';
        $configs['base_url'] = 'https://apiv2.ahrefs.com?mode=subdomains&limit=1&target=';

        $ahrefsInstant = new Ahref($configs);
        $data = $ahrefsInstant->getAhrefsGenerateListAsync($generate_list);
        // $this->updateAhrefData($data);


        return $data;
    }

    private function priceList($code, $type) {
        $score1 = [
            'A' => 40,
            'B' => 30,
            'C' => 20,
            'D' => 5,
            'E' => -10,
        ];

        $score2 = [
            'A' => 30,
            'B' => 25,
            'C' => 15,
            'D' => 5,
            'E' => -10,
        ];

        $score3 = [
            'A' => 65,
            'B' => 40,
            'C' => 25,
            'D' => 10,
            'E' => 0,
        ];

        $score4 = [
            'A' => 100,
            'B' => 50,
            'C' => 35,
            'D' => 15,
            'E' => 0,
        ];

        switch($type) {
            case 'score1': 
                return $score1[$code];
            case 'score2': 
                return $score2[$code];
            case 'score3': 
                return $score3[$code];
            case 'score4': 
                return $score4[$code];
            default:
                return 0;
        }
    }

    public function computePrice(Request $request) {
        if( is_array($request->ids) ) {
            foreach($request->ids as $id) {
                $generate_list = GenerateList::where('id', $id)->first();

                $code_1 = $this->compute($generate_list->ur, $generate_list->dr, 'value1');
                $code_2 = $this->compute($generate_list->backlinks, $generate_list->ref_domain, 'value2');
                $code_3 = $this->compute($generate_list->org_kw, 0, 'value3');
                $code_4 = $this->compute($generate_list->org_traffic, 0, 'value4');
                $code_comb = $code_1.$code_2.$code_3.$code_4;

                // $price_list = Pricelist::where('code', strtoupper($code_comb))->first();

                $score1 = $this->priceList($code_1, 'score1');
                $score2 = $this->priceList($code_2, 'score2');
                $score3 = $this->priceList($code_3, 'score3');
                $score4 = $this->priceList($code_4, 'score4');

                $cons = 4.25;

                $generate_list->update([
                    'code_1' => $code_1,
                    'code_2' => $code_2,
                    'code_3' => $code_3,
                    'code_4' => $code_4,
                    'code_comb' => $code_comb,
                    // 'price' => isset($price_list->price) ? $price_list->price : 0
                    'price' => round((($score1 + $score2 + $score3 + $score4) / 4) * $cons, 0)
                ]);

            }
        }

    }

    private function checkCombination($val) {
        $combination = [
            'AAA' => 'A',
            'AAB' => 'A',
            'AAC' => 'B',
            'AAD' => 'C',
            'AAE' => 'B',

            'BBA' => 'A',
            'BBB' => 'B',
            'BBC' => 'B',
            'BBD' => 'C',
            'BBE' => 'D',

            'CCA' => 'B',
            'CCB' => 'C',
            'CCC' => 'C',
            'CCD' => 'D',
            'CCE' => 'E',

            'DDA' => 'D',
            'DDB' => 'D',
            'DDC' => 'E',
            'DDD' => 'E',
            'DDE' => 'E',

            'EEA' => 'E',
            'EEB' => 'E',
            'EEC' => 'E',
            'EED' => 'E',
            'EEE' => 'E',

            'BAA' => 'A',
            'BAB' => 'A',
            'BAC' => 'B',
            'BAD' => 'B',

            'BCC' => 'B',
            'BCD' => 'C',
            'BCE' => 'E',

            'BDC' => 'C',
            'BDD' => 'D',
            'BDE' => 'E',
            'BEE' => 'E',

            'CAC' => 'D',
            'CAD' => 'E',

            'CBC' => 'D',
            'CBB' => 'C',
            'CBD' => 'E',

            'CDD' => 'D',
            'CEE' => 'E',

            'DAD' => 'D',
            'DBC' => 'D',
            'DCA' => 'D',
            'DCB' => 'D',
            'DCC' => 'E'
        ];

        if(array_key_exists($val , $combination)) {
            return $combination[$val];
        } else {
            return 'E';
        }
    }

    private function compute( $a , $b, $type )
    {

        switch ( $type ) {

            case "value1":
                $val = 'E';
                $comb1 = ''; // UR
                $comb2 = ''; // UR
                $comb3 = ''; // Condition 2

                // UR
                if($a >= 50 && $a <= 100) {
                    $comb1 = 'A';
                } else if($a >= 26 && $a <= 49) {
                    $comb1 = 'B';
                } else if($a >= 10 && $a <= 25) {
                    $comb1 = 'C';
                } else if($a >= 6 && $a <= 9) {
                    $comb1 = 'D';
                } else if($a >= 0 && $a <= 5) {
                    $comb1 = 'E';
                }

                // DR
                if($b >= 50 && $b <= 100) {
                    $comb2 = 'A';
                } else if($b >= 26 && $b <= 49) {
                    $comb2 = 'B';
                } else if($b >= 10 && $b <= 25) {
                    $comb2 = 'C';
                } else if($b >= 6 && $b <= 9) {
                    $comb2 = 'D';
                } else if($b == 0 && $b <= 5) {
                    $comb2 = 'E';
                }

                $score = $b - $a;
                //condition 2
                if($score >= 0 && $score <= 7) {
                    $comb3 = 'A';
                } else if($score >= 8 && $score <= 15) {
                    $comb3 = 'B';
                } else if( ($score >= 16 && $score <= 21) || ($score >= -5 && $score <= -1) ) {
                    $comb3 = 'C';
                } else if( ($score >= 22 ) || ($score >= -10 && $score <= -6) ) {
                    $comb3 = 'D';
                } else if ($score <= -11){
                    $comb3 = 'E';
                }

                $val = $this->checkCombination($comb1.$comb2.$comb3);
                
                return $val;

            case "value2":

                $val = '';

                if( $a == 0 || $b == 0){
                    $score = 0;
                    $val = 'E';
                }else{
                    $score = number_format( floatVal($a / $b) , 0, '.', '');
                }

                if( $score >= 0.99 && $score <= 5){  $val = 'A'; }
                else if( $score >= 6 && $score <= 16){ $val = 'B'; }
                else if( $score >= 17 && $score <= 22){ $val = 'C'; }
                else if( $score >= 23 && $score <= 60){ $val = 'D'; }
                else if( $score >= 61 ){ $val = 'E'; }
                else if( $score < 0.99 ){ $val = 'C'; }

                return $val;

            case "value3":

                $val = '';

                if( $a >= 1000){ $val = 'A'; }
                else if( $a > 501 && $a < 1000){ $val = 'B'; }
                else if( $a >= 100 && $a <= 500){ $val = 'C'; }
                else if( $a >= 50 && $a <= 99){ $val = 'D'; }
                else if( $a <= 49 ){ $val = 'E'; }

                return $val;

            case "value4":

                $val = '';

                if( $a >= 10000){ $val = 'A'; }
                else if( $a >= 3501 && $a < 10000){ $val = 'B'; }
                else if( $a >= 1001 && $a <= 3500){ $val = 'C'; }
                else if( $a >= 500 && $a <= 1000){ $val = 'D'; }
                else if( $a <= 499 ){ $val = 'E'; }

                return $val;

            default:

                return '';

        }

    }


}
