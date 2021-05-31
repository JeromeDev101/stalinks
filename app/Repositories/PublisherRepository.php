<?php

namespace App\Repositories;

use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\ExtDomain;
use App\Models\Publisher;
use App\Models\Registration;
use App\Libs\Ahref;
use App\Services\HttpClient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Country;
use App\Models\Pricelist;
use App\Models\Language;

class PublisherRepository extends BaseRepository implements PublisherRepositoryInterface {
    protected $extDomain;

    protected $httpClient;

    public function __construct(Publisher $model, HttpClient $httpClient)
    {
        parent::__construct($model);

        $this->httpClient = $httpClient;
    }

    public function getList($filter)
    {
        $user = Auth::user();
        $paginate = (isset($filter['paginate']) && !empty($filter['paginate']) ) ? $filter['paginate']:50;

        $paginate = $filter['price_basis'] == '' ? $paginate:Publisher::count();

        $columns = [
            'publisher.*',
            'registration.username',
            'registration.account_validation as user_account_validation',
            'A.name',
            'A.username as user_name',
            'A.isOurs',
            'registration.company_name',
            'countries.name AS country_name',
            'country_continent.name AS country_continent',
            'publisher_continent.name AS publisher_continent',
            'languages.name AS language_name',
            'B.username AS in_charge',
            'B.id AS team_in_charge',
        ];
        $list = Publisher::select($columns)
                ->leftJoin('users as A', 'publisher.user_id', '=', 'A.id')
                ->leftJoin('registration', 'A.email', '=', 'registration.email')
                ->leftJoin('users as B', 'registration.team_in_charge', '=', 'B.id')
                ->leftJoin('countries', 'publisher.country_id', '=', 'countries.id')
                ->leftJoin('continents as country_continent', 'countries.continent_id', '=', 'country_continent.id')
                ->leftJoin('continents as publisher_continent', 'publisher.continent_id', '=', 'publisher_continent.id')
                ->leftJoin('languages', 'publisher.language_id', '=', 'languages.id');

        if (isset($filter['show_duplicates']) && $filter['show_duplicates'] === 'yes') {
            $validFilter = isset($filter['valid']) ? 'AND valid IN ('. implode(',', implode_array_to_strings($filter['valid'])) .')' : '';

            $list = $list->join(DB::raw('(
                    SELECT url
                    FROM publisher
                    WHERE deleted_at IS NULL
                    '. $validFilter .'
                    GROUP BY url
                    HAVING COUNT(*) > 1
                    )temp'), 'publisher.url', 'temp.url')
                ->orderBy('url', 'asc');
        } else {
            $list->orderBy('created_at', 'desc');
        }

        if( isset($filter['account_validation']) && !empty($filter['account_validation']) ){
            $list = $list->where('registration.account_validation', $filter['account_validation']);
        }

        if( $user->type != 10 && isset($user->registration->type) && $user->registration->type == 'Seller' ){
            $list->where('publisher.user_id', $user->id);
        }

        if( isset($filter['seller']) && !empty($filter['seller']) ){
            $list->where('publisher.user_id', $filter['seller']);
        }

        if( $user->isOurs != 0 && $user->type != 10 ){
            $list = $list->where('A.id', $user->id);
        }

        if( isset($filter['in_charge']) && !empty($filter['in_charge']) ){
            $list = $list->where('B.id', $filter['in_charge']);
        }

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list = $list->where('publisher.url', 'like', '%'.$filter['search'].'%');
        }

        if( isset($filter['kw_anchor']) && !empty($filter['kw_anchor']) ){
            $list = $list->where('publisher.kw_anchor', $filter['kw_anchor']);
        }

        if (isset($filter['is_https'])) {
            $list = $list->where('publisher.is_https', $filter['is_https']);
        }

        if( isset($filter['qc_validation']) && !empty($filter['qc_validation']) ){
            if( $filter['qc_validation'] == 'na' ) {
                $list = $list->whereNull('publisher.qc_validation');
            } else {
                $list = $list->where('publisher.qc_validation', $filter['qc_validation']);
            }
        }

        if( isset($filter['got_ahref']) && !empty($filter['got_ahref']) ){
            if( $filter['got_ahref'] == 'Without' ){
                $list = $list->whereNull('publisher.href_fetched_at');
            }

            if( $filter['got_ahref'] == 'With' ){
                $list = $list->whereNotNull('publisher.href_fetched_at');
            }
        }

        if (isset($filter['date'])) {
            $filter['date'] = \GuzzleHttp\json_decode($filter['date'], true);
            if ($filter['date']['startDate'] != null && $filter['date']['endDate'] != null) {
                $list = $list->where('publisher.updated_at', '>=', Carbon::create( $filter['date']['startDate'])->format('Y-m-d'));
                $list = $list->where('publisher.updated_at', '<=', Carbon::create($filter['date']['endDate'])->format('Y-m-d'));
            }
        }

        if (isset($filter['uploaded'])) {
            $filter['uploaded'] = \GuzzleHttp\json_decode($filter['uploaded'], true);
            if ($filter['uploaded']['startDate'] != null && $filter['uploaded']['endDate'] != null) {
                $list = $list->where('publisher.created_at', '>=', Carbon::create( $filter['uploaded']['startDate'])->format('Y-m-d'));
                $list = $list->where('publisher.created_at', '<=', Carbon::create($filter['uploaded']['endDate'])->format('Y-m-d'));
            }
        }

        if( isset($filter['language_id']) && !empty($filter['language_id']) ){
            if( $filter['language_id'] === 'na' ) {
                $list = $list->whereNull('publisher.language_id');
            } else {
                $list = $list->where('publisher.language_id', $filter['language_id']);
            }
        }

        if( isset($filter['casino_sites']) && !empty($filter['casino_sites']) ){
            $list = $list->where('publisher.casino_sites', $filter['casino_sites']);
        }

        if( isset($filter['topic']) && !empty($filter['topic']) ){
            if(is_array($filter['topic'])) {
                // foreach($filter['topic'] as $topic) {

                    // $list = $list->orWhere('publisher.topic', 'like', '%'.$topic.'%');

                    $list = $list->where(function($query) use ($filter) {
                        if( in_array('N/A', $filter['topic']) ) {
                            foreach($filter['topic'] as $topic) {
                                $query->orWhere('publisher.topic', 'like', '%'.$topic.'%')
                                    ->orWhereNull('publisher.topic');
                            }
                        } else {
                            foreach($filter['topic'] as $topic) {
                                $query->orWhere('publisher.topic', 'like', '%'.$topic.'%');
                            }
                        }
                    });

                // }
            } else {
                $list = $list->where('publisher.topic', 'like', '%'. $filter['topic'].'%');
            }
        }

        if( isset($filter['inc_article']) && !empty($filter['inc_article']) ){
            $list = $list->where('publisher.inc_article', $filter['inc_article']);
        }

        if( isset($filter['valid']) && !empty($filter['valid']) ){
            if( is_array($filter['valid']) ){
                $list = $list->whereIn('publisher.valid', $filter['valid']);
            }else{
                $list = $list->where('publisher.valid', $filter['valid']);
            }
        }

        if (isset($filter['continent_id']) && !empty($filter['continent_id'])) {
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
        }

        if (isset($filter['country_id']) && !empty($filter['country_id'])) {
            $list = $list->where('publisher.country_id', $filter['country_id']);
        }

        if (isset($filter['price_basis']) && !empty($filter['price_basis'])) {
            if (is_array($filter['price_basis'])) {
                $list = $list->whereIn('price_basis', $filter['price_basis']);
            } else {
                $list = $list->where('price_basis', $filter['price_basis']);
            }
        }

        if (isset($filter['domain_zone']) && !empty($filter['domain_zone'])) {
            if (is_array($filter['domain_zone'])) {

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


        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){

            $result = $list->get();
        }else{
            $result = $list->paginate($paginate);
        }

        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            return response()->json([
                'data' => $result,
                'total' => $result->count(),
            ],200);
        }else{
            return $result;
        }
    }

    /**
     *
     * get code combination of list publisher
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
                
                // ur = $a
                // dr = $b

                $val = '';
                           
                if( ($a >= 0 && $a <= 9) || ($b >= 0 && $b <= 9) ){
                    $val = 'E';
                    return $val;
                }
                
                
                if( ($a >= 10 && $a <= 100) && ($b >= 10 && $b <= 19) ) {
                    
                    if( $score >= -9 && $score <= 9 ) {
                        $val = 'D';
                    } else {
                        $val = 'E';
                    }
                    return $val;
                }
                
                if( ($a >= 10 && $a <= 19) && ($b >= 20 && $b <= 100) ) {
                    
                    if( $score >= 1 && $score <= 15 ) {
                        $val = 'D';
                    } else {
                        $val = 'E';
                    }
                    return $val;
                }
                
                
                if( ($a >= 20 && $a <= 100) && ($b >= 20 && $b <= 34) ) {
                    
                    if( $score >= -15 && $score <= -1 ) {
                        $val = 'D';
                    } else if( $score <= -16 ) {
                        $val = 'E';
                    } else {
                        $val = 'C';
                    }
                    return $val;
                }
                
                
                if( ($a >= 20 && $a <= 34) && ($b >= 35 && $b <= 100) ) {
                    
                    if( $score >= 1 && $score <= 16 ) {
                        $val = 'B';
                    } else {
                        $val = 'C';
                    }
                    return $val;
                }
                
                
                if( ($a >= 35 && $a <= 49) && ($b >= 35 && $b <= 49) ) {
                    
                    if( $score >= -9 && $score <= 9 ) {
                        $val = 'B';
                    } else {
                        $val = 'C';
                    }
                    return $val;
                }
                
                
                if( ($a >= 50 && $a <= 100) && ($b >= 35 && $b <= 49) ) {
                    
                    if( $score >= -15 && $score <= -5 ) {
                        $val = 'D';
                    } else if( $score <= -16 ) {
                        $val = 'E';
                    } else {
                        $val = 'A';
                    }
                    return $val;
                }
                
                
                if( ($a >= 35 && $a <= 49) && ($b >= 50 && $b <= 100) ) {
                    
                    if( $score >= 1 && $score <= 5 ) {
                        $val = 'A';
                    } else {
                        $val = 'B';
                    }
                    return $val;
                }
                
                if( ($a >= 50 && $a <= 100) && ($b >= 50 && $b <= 100) ) {
                    
                    if( $score >= -5 && $score <= 15 ) {
                        $val = 'A';
                    } else {
                        $val = 'B';
                    }
                    return $val;
                }

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

    public function importExcel($file){
        $user_id_list = User::pluck('id')->toArray();
        $country_name_list = Country::pluck('name')->toArray();
        $language_name_list = Language::pluck('name')->toArray();
        $topic_list = [
            'Art',
            'Beauty',
            'Charity',
            'Cooking',
            'Crypto',
            'Education',
            'Fashion',
            'Finance',
            'Games',
            'Health',
            'History',
            'Job',
            'Marketing',
            'Movies & Music',
            'News',
            'Pet',
            'Photograph',
            'Real Estate',
            'Religion',
            'Shopping',
            'Sports',
            'Tech',
            'Travel',
            'Unlisted',
        ];
//        $language = $file['language'];
        $csv_file = $file['file'];

        $result = true;
        $message = '';
        $file_message = '';

        $id = Auth::user()->id;
        $csv = fopen($csv_file, 'r');
        $ctr = 0;

        $datas = [];
        $existing_datas = [];
        while ( ($line = fgetcsv($csv) ) !== FALSE) {
            if (Auth::user()->isOurs == 1){

                if(count($line) > 7 || count($line) < 7){
                    $message = "Please check the header: Url, Price, Inc Article, Accept, KW Anchor, Language and Topic only";
                    $file_message = "Invalid Header format. ".$message;
                    $result = false;
                    break;
                }

                if( $ctr > 0 ){
                    $url = trim_special_characters($line[0]);
                    $price = trim_special_characters($line[1]);
                    $article = trim_special_characters($line[2]);
                    $accept = trim_special_characters($line[3]);
                    $kw_anchor = trim_special_characters($line[4]);
                    $language_excel = trim_special_characters($line[5]);
                    $topic = trim_special_characters($line[6]);

                    $isCheckDuplicate  = $this->checkDuplicate($url, $id);

                    if (preg_grep("/".$language_excel."/i", $language_name_list)){

                        if (!$isCheckDuplicate) {

                            if( trim($url, " ") != '' ){
                                $url_remove_http = $this->remove_http($url);
                                $lang = $this->getCountry($language_excel);
                                $valid = $this->checkValid($url_remove_http);

                                Publisher::create([
                                    'user_id' => $id,
                                    'language_id' => $lang,
                                    'url' => $url_remove_http,
                                    'ur' => 0,
                                    'dr' => 0,
                                    'backlinks' => 0,
                                    'ref_domain' => 0,
                                    'org_keywords' => 0,
                                    'org_traffic' => 0,
                                    'price' => preg_replace('/[^0-9.\-]/', '', $price),
                                    'inc_article' => ucwords( strtolower( trim($article, " ") ) ),
                                    'valid' => $valid,
                                    'casino_sites' => ucwords( strtolower( trim($accept, " ") ) ),
                                    'kw_anchor' => ucwords( strtolower( trim($kw_anchor, " ") ) ),
                                    'topic' => $topic,
                                    'is_https' => $this->httpClient->getProtocol($url_remove_http) == 'https' ? 'yes' : 'no',
                                ]);
                            }
                        } else {
                            $file_message = "You have already uploaded this url: " . $url . ", Check in line ". (intval($ctr) + 1);
                            $result = true;

                            array_push($existing_datas, [
                                'message' => $file_message
                            ]);
                        }

                    } else {
                        $file_message = "No Language name of ". $language_excel . $message . ". Check in line ". (intval($ctr) + 1);
                        $result = true;

                        array_push($existing_datas, [
                            'message' => $file_message
                        ]);
                    }
                }

            } else {

                if(count($line) > 8 || count($line) < 8){
                    $message = "Please check the header: Url, Price, Inc Article, Seller ID, Accept, Language, Topic and Kw Anchor only.";
                    $file_message = "Invalid Header format. ".$message;
                    $result = false;
                    break;
                }

                if( $ctr > 0 ){
                    $url = trim_special_characters($line[0]);
                    $price = trim_special_characters($line[1]);
                    $article = trim_special_characters($line[2]);
                    $seller_id = trim_special_characters($line[3]);
                    $accept = trim_special_characters($line[4]);
                    $language_excel = trim_special_characters($line[5]);
                    $topic = trim_special_characters($line[6]);
                    $kw_anchor = trim_special_characters($line[7]);

                    $isCheckDuplicate  = $this->checkDuplicate($url, $seller_id);

                    if (in_array($seller_id, $user_id_list)){

                        if (preg_grep("/".$language_excel."/i", $language_name_list)){

                            if (preg_grep("/".$topic."/i", $topic_list)){

                                if (!$isCheckDuplicate) {
                                    if( trim($url, " ") != '' ){
                                        $url_remove_http = $this->remove_http($url);
                                        $lang = $this->getCountry($language_excel);
                                        $valid = $this->checkValid($url_remove_http);
                                        Publisher::create([
                                            'user_id' => $seller_id ,
                                            'language_id' => $lang,
                                            'url' => $url_remove_http,
                                            'ur' => 0,
                                            'dr' => 0,
                                            'backlinks' => 0,
                                            'ref_domain' => 0,
                                            'org_keywords' => 0,
                                            'org_traffic' => 0,
                                            'price' => preg_replace('/[^0-9.\-]/', '', $price),
                                            'inc_article' => ucwords( strtolower( trim($article, " ") ) ),
                                            'valid' => $valid,
                                            'casino_sites' => ucwords( strtolower( trim($accept, " ") ) ),
                                            'topic' => $topic,
                                            'kw_anchor' => $kw_anchor,
                                            'is_https' => $this->httpClient->getProtocol($url_remove_http) == 'https' ? 'yes' : 'no',
                                        ]);
                                    }
                                } else {
                                    $file_message = " URL and Seller ID already exist, Check in line ". (intval($ctr) + 1);
                                    $result = true;

                                    array_push($existing_datas, [
                                        'message' => $file_message
                                    ]);
                                }

                            } else{

                                $file_message = " No Topic name of ". $topic . $message . ". Check in line ". (intval($ctr) + 1);
                                $result = true;

                                array_push($existing_datas, [
                                    'message' => $file_message
                                ]);
                            }

                        } else {
                            $file_message = "No Language name of ". $language_excel . $message . ". Check in line ". (intval($ctr) + 1);
                            $result = true;

                            array_push($existing_datas, [
                                'message' => $file_message
                            ]);
                        }

                    } else{
                        $file_message = "No Seller ID of ". $seller_id . $message . ". Check in line ". (intval($ctr) + 1);
                        $result = true;

                        array_push($existing_datas, [
                            'message' => $file_message
                        ]);
                    }
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
            ],
            "exist" => $existing_datas,
        ];
    }

    private function checkDuplicate($url, $seller_id) {
        $publisher = Publisher::where('url', 'like', '%'.$url.'%')->where('user_id', $seller_id);
        return $publisher->count() > 0;
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

    private function checkValid($url) {
        $result = 'valid';
        $publisher = Publisher::where('url', 'like', '%'.$url.'%')->where('valid', 'valid')->count();
        if ($publisher > 0) {
            $result = 'unchecked';
        } else {
            $result = 'valid';
        }

        return $result;
    }

    private function checkUrlAndSeller($seller_id, $url) {
        $result = true;
        $publisher = Publisher::where('user_id', $seller_id)->where('url', 'like', '%'.$url.'%')->first();
        if (isset($publisher->id)) {
            $result = false;
        }
        return $result;
    }

    private function getCountry($country){
        $id = 5;
        $country = Language::where('name', 'like', '%'.$country.'%')->first();
        if( $country ){
            $id = $country->id;
        }
        return $id;
    }

    /**
     * Get ahrefs with promise for request async concurrency
     * @param array $listIds
     * @param array $configs
     * @return array
     */
    public function getAhrefs($listIds, $configs)
    {
        $publishers = Publisher::whereIn('id', $listIds)->get();

        if ($publishers->count() == 0) {
            return [];
        }

        $ahrefsInstant = new Ahref($configs);
        $data = $ahrefsInstant->getAhrefsPublisherAsync($publishers);
        $this->updateAhrefData($data);


        return $data;
    }

    public function updateAhrefData($publisher)
    {
        $priceCollection = Pricelist::all();

        foreach($publisher as $key => $value) {
            $codeCombiURDR = $this->getCodeCombination($value->ur, $value->dr, 'value1');
            $codeCombiBlRD = $this->getCodeCombination($value->backlinks, $value->ref_domain, 'value2');
            $codeCombiOrgKW = $this->getCodeCombination($value->org_keywords, 0, 'value3');
            $codeCombiOrgT = $this->getCodeCombination($value->org_traffic, 0, 'value4');
            $combineALl = $codeCombiURDR. $codeCombiBlRD .$codeCombiOrgKW. $codeCombiOrgT;

            $price_list = $priceCollection->where('code', strtoupper($combineALl));

            $value['code_combination'] = $combineALl;
            $value['code_price'] = ( isset($price_list['price']) && !empty($price_list['price']) ) ? $price_list['price']:0;


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
                        $price_basis = 'Good';
                    }

                    if( $var_a > $result_1 && $result_1 < $result_2 ){
                        $price_basis = 'Average';
                    }

                    if( $var_a > $result_2 ){
                        $price_basis = 'High';
                    }
                }
            }

            if( $value['code_price'] == 0 && $value->price > 0){
                $price_basis = 'High';
            }

            Publisher::find($value->id)->update([
                'code_comb' => $value['code_combination'],
                'code_price' => $value['code_price'],
                'price_basis' => $price_basis,
                'href_fetched_at' => Carbon::now()
            ]);
        }
    }

    /**
     * get summary publisher list
     *
     */

     public function getPublisherSummary($user_id)
     {
         $publishers = $this->model->selectRaw('language_id, count(DISTINCT(id)) as total')
                            ->where('user_id', $user_id)
                            ->with(['country' => function($query) {
                                $query->select('id', 'name', 'code');
                            }])
                            ->groupBy('language_id')
                            ->distinct()
                            ->get();

        $dataReturn = [];
        $sumTotal = 0;

        foreach($publishers as $item) {
            $sumTotal += $item->total;
        }

        return [
            'total' => $sumTotal,
            'data' => $publishers
        ];
     }

     private function initArrayReport(&$dataReturn, $key, $country) {
        $dataReturn[$key] = ['country' => $country];
        foreach($this->statusList as $value) {
            $dataReturn[$key][$value] = 0;
        }
    }
}
