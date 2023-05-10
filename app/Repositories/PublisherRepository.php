<?php

namespace App\Repositories;

use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\ExtDomain;
use App\Models\Publisher;
use App\Models\Registration;
use App\Libs\Ahref;
use App\Rules\ValidTopic;
use App\Rules\ValidUrl;
use App\Services\HttpClient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Country;
use App\Models\Pricelist;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use SplFileObject;

class PublisherRepository extends BaseRepository implements PublisherRepositoryInterface
{
    protected $extDomain;

    protected $httpClient;

    private $topic_list = [
        'Adult',
        'Art',
        'Automotive',
        'Beauty',
        'CBD',
        'Charity',
        'Cooking',
        'Crypto',
        'Education',
        'Fashion',
        'Finance',
        'Gambling',
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

    public function __construct()
    {
        parent::__construct(new Publisher());

        $this->httpClient = new HttpClient();
    }

    public function getList($filter)
    {
        $user = Auth::user();
        $paginate = (isset($filter['paginate']) && !empty($filter['paginate'])) ? $filter['paginate'] : 50;

        $paginate = $filter['price_basis'] == '' ? $paginate : Publisher::count();

//        $columns = [
//            'publisher.*',
//            'registration.username',
//            'registration.account_validation as user_account_validation',
//            'A.name',
//            'A.username as user_name',
//            'A.isOurs',
//            'registration.company_name',
//            'countries.name AS country_name',
//            'country_continent.name AS country_continent',
//            'publisher_continent.name AS publisher_continent',
//            'languages.name AS language_name',
//            'B.username AS in_charge',
//            'B.id AS team_in_charge'
//        ];

        $list = Publisher::select(
            'publisher.*',
            'registration.username',
            'registration.account_validation as user_account_validation',
            'registration.is_recommended',
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
            DB::raw('(
                        CASE
                            WHEN publisher_continent.name IS NULL and country_continent.name is NULL THEN null
                            WHEN publisher_continent.name IS NULL THEN country_continent.name
                            WHEN country_continent.name IS NULL THEN publisher_continent.name
                            ELSE publisher_continent.name
                        END
                    ) AS continent_name'))
            ->leftJoin('users as A', 'publisher.user_id', '=', 'A.id')
            ->leftJoin('registration', 'A.email', '=', 'registration.email')
            ->leftJoin('users as B', 'registration.team_in_charge', '=', 'B.id')
            ->leftJoin('countries', 'publisher.country_id', '=', 'countries.id')
            ->leftJoin('continents as country_continent', 'countries.continent_id', '=', 'country_continent.id')
            ->leftJoin('continents as publisher_continent', 'publisher.continent_id', '=', 'publisher_continent.id')
            ->leftJoin('languages', 'publisher.language_id', '=', 'languages.id')
            // ->where('registration.account_validation', '=', 'valid')
            ->has('user');

        if (isset($filter['show_duplicates']) && $filter['show_duplicates'] === 'yes') {

            $list = $list->join(DB::raw('(
                    SELECT url, valid
                    FROM publisher
                    WHERE deleted_at IS NULL
                    GROUP BY url
                    HAVING COUNT(*) > 1
                    )temp'), 'publisher.url', 'temp.url')
                ->orderBy('url', 'asc');
        } else if (isset($filter['show_duplicates']) && $filter['show_duplicates'] === 'no') {
            $list = $list->join(DB::raw('(
                    SELECT url, valid
                    FROM publisher
                    WHERE deleted_at IS NULL
                    GROUP BY url
                    HAVING COUNT(*) < 2
                    )temp'), 'publisher.url', 'temp.url')
                ->orderBy('url', 'asc');
        } else {
//            $list->orderBy('created_at', 'desc');
        }

        if (isset($filter['account_validation']) && !empty($filter['account_validation'])) {
            $list = $list->where('registration.account_validation', $filter['account_validation']);
        }

        if ($user->type != 10 && isset($user->registration->type) && $user->registration->type == 'Seller') {
            $list->where('publisher.user_id', $user->id);
        }

        if (isset($filter['seller']) && !empty($filter['seller'])) {
            $list->where('publisher.user_id', $filter['seller']);
        }

        if ($user->isOurs != 0 && $user->type != 10) {
            $list = $list->where('A.id', $user->id);
        }

        if (isset($filter['in_charge']) && !empty($filter['in_charge'])) {
            $list = $list->where('B.id', $filter['in_charge']);
        }

        if (isset($filter['search']) && !empty($filter['search'])) {
            $list = $list->where('publisher.url', 'like', '%' . $filter['search'] . '%');
        }

        if (isset($filter['kw_anchor']) && !empty($filter['kw_anchor'])) {
            $list = $list->where('publisher.kw_anchor', $filter['kw_anchor']);
        }

        if (isset($filter['is_https'])) {
            $list = $list->where('publisher.is_https', $filter['is_https']);
        }

        if (isset($filter['qc_validation']) && !empty($filter['qc_validation'])) {
            if ($filter['qc_validation'] == 'na') {
                $list = $list->whereNull('publisher.qc_validation');
            } else {
                $list = $list->where('publisher.qc_validation', $filter['qc_validation']);
            }
        }

        if (isset($filter['got_ahref']) && !empty($filter['got_ahref'])) {
            if ($filter['got_ahref'] == 'Without') {
                $list = $list->whereNull('publisher.href_fetched_at');
            }

            if ($filter['got_ahref'] == 'With') {
                $list = $list->whereNotNull('publisher.href_fetched_at');
            }
        }

        if (isset($filter['date'])) {
            $filter['date'] = \GuzzleHttp\json_decode($filter['date'], true);
            if ($filter['date']['startDate'] != null && $filter['date']['endDate'] != null) {
                $list = $list->whereDate('publisher.updated_at', '>=', Carbon::create($filter['date']['startDate'])->format('Y-m-d'));
                $list = $list->whereDate('publisher.updated_at', '<=', Carbon::create($filter['date']['endDate'])->format('Y-m-d'));
            }
        }

        if (isset($filter['uploaded'])) {
            $filter['uploaded'] = \GuzzleHttp\json_decode($filter['uploaded'], true);
            if ($filter['uploaded']['startDate'] != null && $filter['uploaded']['endDate'] != null) {
                $list = $list->whereDate('publisher.created_at', '>=', Carbon::create($filter['uploaded']['startDate'])->format('Y-m-d'));
                $list = $list->whereDate('publisher.created_at', '<=', Carbon::create($filter['uploaded']['endDate'])->format('Y-m-d'));
            }
        }

        if (isset($filter['language_id']) && !empty($filter['language_id'])) {
            if ($filter['language_id'] === 'na') {
                $list = $list->whereNull('publisher.language_id');
            } else {
                $list = $list->where('publisher.language_id', $filter['language_id']);
            }
        }

        if (isset($filter['casino_sites']) && !empty($filter['casino_sites'])) {
            $list = $list->where('publisher.casino_sites', $filter['casino_sites']);
        }

        if (isset($filter['topic']) && !empty($filter['topic'])) {
            if (is_array($filter['topic'])) {
                // foreach($filter['topic'] as $topic) {

                // $list = $list->orWhere('publisher.topic', 'like', '%'.$topic.'%');

                $list = $list->where(function ($query) use ($filter) {
                    if (in_array('N/A', $filter['topic'])) {
                        foreach ($filter['topic'] as $topic) {
                            $query->orWhere('publisher.topic', 'like', '%' . $topic . '%')
                                ->orWhereNull('publisher.topic');
                        }
                    } else {
                        foreach ($filter['topic'] as $topic) {
                            $query->orWhere('publisher.topic', 'like', '%' . $topic . '%');
                        }
                    }
                });
                // }
            } else {
                $list = $list->where('publisher.topic', 'like', '%' . $filter['topic'] . '%');
            }
        }

        if (isset($filter['inc_article']) && !empty($filter['inc_article'])) {
            $list = $list->where('publisher.inc_article', $filter['inc_article']);
        }

        if (isset($filter['valid']) && !empty($filter['valid'])) {
            if (is_array($filter['valid'])) {
                $list = $list->whereIn('publisher.valid', $filter['valid']);
            } else {
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

                if (!empty($filter['continent_id'])) {
                    $query->orWhereIn('countries.continent_id', $filter['continent_id'])
                        ->orWhereIn('publisher.continent_id', $filter['continent_id']);
                }
            });
        }

//        if (isset($filter['country_id']) && !empty($filter['country_id'])) {
//            $list = $list->where('publisher.country_id', $filter['country_id']);
//        }

        if (isset($filter['country_id']) && !empty($filter['country_id'])) {
            if (is_array($filter['country_id'])) {
                $list->whereIn('publisher.country_id', $filter['country_id']);
            } else {
                $list->where('publisher.country_id', $filter['country_id']);
            }
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

        if (isset($filter['sort']) && !empty($filter['sort'])) {
            foreach ($filter['sort'] as &$sort) {
                $sort = \GuzzleHttp\json_decode($sort);
                $list = $list->orderByRaw("$sort->column $sort->sort");
            }
        } else {
            $list->orderBy('created_at', 'desc');
        }

        if (isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All') {
            $result = $list->get();
        } else {
            $result = $list->paginate($paginate);
        }

        if (isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All') {
            return response()->json([
                'data'  => $result,
                'total' => $result->count(),
            ], 200);
        } else {
            return $result;
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

    private function getCodeCombination( $a , $b, $type )
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
                    $val = 'A';
                }else{
                    $score = number_format( floatVal($a / $b) , 2, '.', '');
                }

                if( $score >= 0.99 && $score < 3){  $val = 'A'; }
                else if( $score >= 3 && $score < 8){ $val = 'C'; }
                else if( $score >= 8 && $score < 16){ $val = 'D'; }
                else if( $score >= 16 || $score < 0.99 ){ $val = 'E'; }

                return $val;

            case "value3":

                $val = '';

                if( $a >= 500){ $val = 'A'; }
                else if( $a >= 200 && $a < 500){ $val = 'B'; }
                else if( $a >= 100 && $a < 200){ $val = 'C'; }
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

    public function importExcelTwo ($request) {
        $valid = 0;
        $invalid = 0;
        $rows = [];
        $result = true;
        $message = '';
        $file_message = '';
        $existing_data = [];

        $country_name_list = Country::pluck('name')->toArray();
        $language_name_list = Language::pluck('name')->toArray();

        $headers = Auth::user()->isOurs == 1
            ? [
                'URL',
                'Price',
                'Inc Article',
                'Accept C&B',
                'KW Anchor',
                'Language',
                'Topic',
                'Country',
            ]
            : [
                'URL',
                'Price',
                'Inc Article',
                'Seller ID',
                'Accept C&B',
                'Language',
                'Topic',
                'Country',
                'KW Anchor'
            ];

        $path = $request->file('file')->getRealPath();
        $records = array_map('str_getcsv', file($path));

        // store uploaded csv - remove this after we got a sample file

//        $filename = time() . '-' . Auth::user()->id . '-csv.' . $request->file->getClientOriginalExtension();
//        $request->file->move(public_path('/files/csv'), $filename);

        // if records is empty, throw error
        if (count($records) <= 1) {
            $file_message = "Invalid file. Uploaded csv file is empty.";
            $result = false;
        } else {
            // get field names from header column
            $fields = $records[0];

            // if fields are invalid, throw error
            if ($headers !== $fields) {
                $file_message = "Invalid file. Headers must be: " . implode(', ', $headers) . " only";
                $result = false;
            } else {
                // remove the header column
                array_shift($records);

                // check number of lines, must not exceed 500, if invalid throw error
                if (count($records) > 500) {
                    $file_message = "Invalid file: Please upload only 500 urls at a time. Number of urls in file: "
                    . count($records);

                    $result = false;
                } else {

                    // generate item array
                    foreach ($records as $index=>$record) {
                        $record = array_combine($fields, $record);

                        $url = trim_special_characters($record['URL']);
                        $price = trim_special_characters($record['Price']);
                        $article = trim_special_characters($record['Inc Article']);
                        $seller_id = Auth::user()->isOurs == 1
                            ? Auth::user()->id
                            : trim_special_characters($record['Seller ID']);
                        $accept = trim_special_characters($record['Accept C&B']);
                        $language_excel = trim_special_characters($record['Language']);
                        $topic = trim_special_characters($record['Topic']);
                        $country = trim_special_characters($record['Country']);
                        $kw_anchor = trim_special_characters($record['KW Anchor']);

                        // remove http
                        $url = remove_http($url);

                        // remove space
                        $url = trim($url, " ");

                        // remove /
                        $url = rtrim($url,"/");

                        if($url == ''
                            || $price == ''
                            || $topic == ''
                            || $accept == ''
                            || $article == ''
                            || $country == ''
                            || $kw_anchor == ''
                            || $seller_id == ''
                            || $language_excel == '') {

                            $file_message = "Invalid data. Columns cannot be empty. Check in row " . (intval($index) + 1);
                            $result = true;
                            $invalid++;

                            array_push($existing_data, [
                                'message' => $file_message
                            ]);

                        } else {
                            // check language
                            if (!in_array($language_excel, $language_name_list)) {
                                $file_message = "No language name of " . $language_excel . ". Check in row " . (intval($index) + 1);
                                $result = true;
                                $invalid++;

                                array_push($existing_data, [
                                    'message' => $file_message
                                ]);
                            } else {

                                // check country
                                if (!in_array($country, $country_name_list)) {
                                    $file_message = "No country name of " . $country . ". Check in row " . (intval($index) + 1);
                                    $result = true;
                                    $invalid++;

                                    array_push($existing_data, [
                                        'message' => $file_message
                                    ]);
                                } else {
                                    $lang = $this->getLanguage($language_excel);
                                    $count = $this->getCountry($country);

                                    $row = [
                                        'user_id'      => $seller_id,
                                        'language_id'  => $lang,
                                        'continent_id' => $count->continent_id,
                                        'country_id'   => $count->id,
                                        'url'          => $url,
                                        'ur'           => 0,
                                        'dr'           => 0,
                                        'backlinks'    => 0,
                                        'ref_domain'   => 0,
                                        'org_keywords' => 0,
                                        'org_traffic'  => 0,
                                        'price'        => preg_replace('/[^0-9.\-]/', '', $price),
                                        'inc_article'  => ucwords(strtolower(trim($article, " "))),
                                        'valid'        => 'unchecked',
                                        'casino_sites' => ucwords(strtolower(trim($accept, " "))),
                                        'topic'        => $topic,
                                        'kw_anchor'    => $kw_anchor,
                                        'is_https'     => $this->httpClient->getProtocol($url) == 'https' ? 'yes' : 'no',
                                        'from'         => 'csv',
                                    ];

                                    if (count($rows)) {
                                        // check for duplicate seller and url in rows array
                                        $existing_rows = array_filter($rows, function ($var) use ($row) {
                                            return ($var['user_id'] == $row['user_id'] && $var['url'] == $row['url']);
                                        });

                                        if (!count($existing_rows)) {
                                            $rows[$index] = $row;
                                        } else {
                                            $file_message = Auth::user()->isOurs == 1
                                                ? "Duplicate url in csv file. Check in row " . (intval($index) + 2)
                                                : "Duplicate seller and url in csv file. Check in row " . (intval($index) + 2);

                                            $invalid++;

                                            array_push($existing_data, [
                                                'message' => $file_message
                                            ]);
                                        }
                                    } else {
                                        $rows[$index] = $row;
                                    }
                                }
                            }
                        }
                    }

                    if (count($rows)) {
                        $validator = Validator::make(
                            $rows,
                            $this->generateValidationRules($rows),
                            $this->generateValidationMessages($rows, Auth::user())
                        );

                        if ($validator->fails()) {

                            foreach ($validator->errors()->all() as $error) {
                                array_push($existing_data, [
                                    'message' => $error
                                ]);
                            }
                        }

                        // insert valid
                        if (count($validator->valid())) {

                            $for_insert = array_chunk($validator->valid(), 100);

                            // chunk items
                            foreach ($for_insert as $insert_items_array) {
                                foreach ($insert_items_array as $insert) {
                                    Publisher::create($insert);
                                }
                            }
                        }

                        // count valid and invalid
                        $valid = count($validator->valid());
                        $invalid = $invalid + count($validator->invalid());
                    }
                }
            }
        }

        return [
            "valid" => $valid,
            "invalid" => $invalid,
            "success" => $result,
            "message" => $message,
            "errors"  => [
                "file" => $file_message,
            ],
            "exist"   => $existing_data,
        ];
    }

    public function generateValidationRules ($data) {
        $rules = [];

        $user_id_list = User::pluck('id')->toArray();
        $yes_no_values = ['yes', 'no', 'Yes', 'No'];

        foreach ($data as $key => $value){
            $rules[$key . '.url'] = [
                'required',
                'unique:publisher,url,NULL,id,user_id,' . $value['user_id'] . ',deleted_at,NULL',
                new ValidUrl($key)
            ];

            $rules[$key . '.price'] = [
                'required'
            ];

            $rules[$key . '.inc_article'] = [
                'required',
                Rule::in($yes_no_values),
            ];

            $rules[$key . '.user_id'] = [
                'required',
                Rule::in($user_id_list),
            ];

            $rules[$key . '.casino_sites'] = [
                'required',
                Rule::in($yes_no_values),
            ];

            $rules[$key . '.language_id'] = [
                'required'
            ];

            $rules[$key . '.topic'] = [
                'required',
                new ValidTopic($key, $this->topic_list)
            ];

            $rules[$key . '.country_id'] = [
                'required'
            ];

            $rules[$key . '.kw_anchor'] = [
                'required',
                Rule::in($yes_no_values),
            ];
        }

        return $rules;
    }

    public function generateValidationMessages ($data, $user) {
        $messages = [];

        foreach ($data as $key => $value){
            $messages[$key . '.user_id' . '.required'] = 'The Seller ID field is required. Check row ' . ($key + 2);
            $messages[$key . '.user_id' . '.in'] = 'Seller ID not found. Check row ' . ($key + 2);

            $messages[$key . '.url' . '.required'] = 'The URL field is required. Check row ' . ($key + 2);
            $messages[$key . '.url' . '.unique'] = $user->isOurs == 1
                ? 'You have already uploaded this url: :input . Check row ' . ($key + 2)
                : 'The url and seller already exists. Check row ' . ($key + 2);

            $messages[$key . '.price' . '.required'] = 'The price field is required. Check row ' . ($key + 2);

            $messages[$key . '.inc_article' . '.required'] = 'The Inc Article field is required. Check row ' . ($key + 2);
            $messages[$key . '.inc_article' . '.in'] = 'Invalid data. Inc Article column must only be yes/no. Check row ' . ($key + 2);

            $messages[$key . '.kw_anchor' . '.required'] = 'The KW Anchor field is required. Check row ' . ($key + 2);
            $messages[$key . '.kw_anchor' . '.in'] = 'Invalid data. KW Anchor column must only be yes/no. Check row ' . ($key + 2);

            $messages[$key . '.casino_sites' . '.required'] = 'The Accept C&B field is required. Check row ' . ($key + 2);
            $messages[$key . '.casino_sites' . '.in'] = 'Invalid data. Accept C&B column must only be yes/no. Check row ' . ($key + 2);

            $messages[$key . '.topic' . '.required'] = 'The topic field is required. Check row ' . ($key + 2);
        }

        return $messages;
    }

    public function importExcel($file)
    {
        $user_id_list = User::pluck('id')->toArray();
        $country_name_list = Country::pluck('name')->toArray();
        $language_name_list = Language::pluck('name')->toArray();
        $yes_no_values = ['yes', 'no', 'Yes', 'No'];

//        $language = $file['language'];
        $csv_file = $file['file'];

        $result = true;
        $message = '';
        $file_message = '';

        $id = Auth::user()->id;
        $csv = fopen($csv_file, 'r');
        $ctr = 0;

        // check number of lines
        $file_temp = new SplFileObject($csv_file, 'r');

        $file_temp->seek(PHP_INT_MAX);
        $file_line_numbers = $file_temp->key();

        $datas = [];
        $existing_datas = [];

        if ($file_line_numbers > 501) {

            $file_message = "Invalid file: Please upload only 500 urls at a time. Number of urls in file: " . ($file_line_numbers - 1);
            $result = false;

        } else {
            while (($line = fgetcsv($csv)) !== FALSE) {
                if (Auth::user()->isOurs == 1) {

                    if (count($line) > 8 || count($line) < 8) {
                        $message = "Please check the header: Url, Price, Inc Article, Accept C&B, KW Anchor, Language, Topic and Country only.";
                        $file_message = "Invalid Header format. " . $message;
                        $result = false;
                        break;
                    }

                    if ($ctr > 0) {

                        $url = trim_special_characters($line[0]);
                        $price = trim_special_characters($line[1]);
                        $article = trim_special_characters($line[2]);
                        $accept = trim_special_characters($line[3]);
                        $kw_anchor = trim_special_characters($line[4]);
                        $language_excel = trim_special_characters($line[5]);
                        $topic = trim_special_characters($line[6]);
                        $country = trim_special_characters($line[7]);

                        // remove http
                        $url = remove_http($url);

                        // remove space
                        $url = trim($url, " ");

                        // remove /
                        $url = rtrim($url,"/");

                        $isCheckDuplicate = $this->checkDuplicate($url, $id);
                        $isCheckedTopic = $this->checkTopic($topic);

                        // check if url format is valid
                        $isValidURL = $this->isValidURL($url);

                        if($url != ''
                            && $topic != ''
                            && $price != ''
                            && $accept != ''
                            && $article != ''
                            && $country != ''
                            && $kw_anchor != ''
                            && $language_excel != '')  {

                            if ($isValidURL) {

                                // check yes and no values
                                if ( in_array($article, $yes_no_values) && in_array($accept, $yes_no_values) && in_array($kw_anchor, $yes_no_values) ) {

                                    if (preg_grep("/" . $language_excel . "/i", $language_name_list)) {

                                        if ($isCheckedTopic) {

                                            if (preg_grep("/" . $country . "/i", $country_name_list)) {

                                                if (!$isCheckDuplicate) {

                                                    if (trim($url, " ") != '') {
                                                        $lang = $this->getLanguage($language_excel);
                                                        $count = $this->getCountry($country);
                                                        // $valid = $this->checkValid($url);

                                                        Publisher::create([
                                                            'user_id'      => $id,
                                                            'language_id'  => $lang,
                                                            'continent_id' => $count->continent_id,
                                                            'country_id'   => $count->id,
                                                            'url'          => $url,
                                                            'ur'           => 0,
                                                            'dr'           => 0,
                                                            'backlinks'    => 0,
                                                            'ref_domain'   => 0,
                                                            'org_keywords' => 0,
                                                            'org_traffic'  => 0,
                                                            'price'        => preg_replace('/[^0-9.\-]/', '', $price),
                                                            'inc_article'  => ucwords(strtolower(trim($article, " "))),
                                                            // 'valid'        => $valid,
                                                            'valid'        => 'unchecked',
                                                            'casino_sites' => ucwords(strtolower(trim($accept, " "))),
                                                            'kw_anchor'    => ucwords(strtolower(trim($kw_anchor, " "))),
                                                            'topic'        => $topic,
                                                            'is_https'     => $this->httpClient->getProtocol($url) == 'https' ? 'yes' : 'no',
                                                        ]);
                                                    }
                                                } else {
                                                    $file_message = "You have already uploaded this url: " . $url . ", Check in line " . (intval($ctr) + 1);
                                                    $result = true;

                                                    array_push($existing_datas, [
                                                        'message' => $file_message
                                                    ]);
                                                }
                                            } else {
                                                $file_message = "No Country name of " . $country . $message . ". Check in line " . (intval($ctr) + 1);
                                                $result = true;

                                                array_push($existing_datas, [
                                                    'message' => $file_message
                                                ]);
                                            }
                                        } else {
                                            $file_message = " No Topic name of " . $topic . $message . ". Check in line " . (intval($ctr) + 1);
                                            $result = true;

                                            array_push($existing_datas, [
                                                'message' => $file_message
                                            ]);
                                        }
                                    } else {
                                        $file_message = "No Language name of " . $language_excel . $message . ". Check in line " . (intval($ctr) + 1);
                                        $result = true;

                                        array_push($existing_datas, [
                                            'message' => $file_message
                                        ]);
                                    }
                                } else {
                                    $file_message = "Invalid data. Inc Article, Accept C&B and KW Anchor values must be yes/no only. Check line " . (intval($ctr) + 1);
                                    $result = true;

                                    array_push($existing_datas, [
                                        'message' => $file_message
                                    ]);
                                }
                            } else {
                                $file_message = "Invalid url format: " . $url . $message . ". Check in line " . (intval($ctr) + 1);
                                $result = true;

                                array_push($existing_datas, [
                                    'message' => $file_message
                                ]);
                            }

                        } else {
                            $file_message = "Invalid data. Columns cannot be empty. Check in line " . (intval($ctr) + 1);
                            $result = true;

                            array_push($existing_datas, [
                                'message' => $file_message
                            ]);
                        }
                    }
                } else {
                    if (count($line) > 9 || count($line) < 9) {
                        $message = "Please check the header: Url, Price, Inc Article, Seller ID, Accept C&B, Language, Topic, Country and Kw Anchor only.";
                        $file_message = "Invalid Header format. " . $message;
                        $result = false;
                        break;
                    }

                    if ($ctr > 0) {
                        $url = trim_special_characters($line[0]);
                        $price = trim_special_characters($line[1]);
                        $article = trim_special_characters($line[2]);
                        $seller_id = trim_special_characters($line[3]);
                        $accept = trim_special_characters($line[4]);
                        $language_excel = trim_special_characters($line[5]);
                        $topic = trim_special_characters($line[6]);
                        $country = trim_special_characters($line[7]);
                        $kw_anchor = trim_special_characters($line[8]);

                        // remove http
                        $url = remove_http($url);

                        // remove space
                        $url = trim($url, " ");

                        // remove /
                        $url = rtrim($url,"/");

                        $isCheckDuplicate = $this->checkDuplicate($url, $seller_id);
                        $isCheckedTopic = $this->checkTopic($topic);

                        // check if url format is valid
                        $isValidURL = $this->isValidURL($url);

                        if($url != ''
                            && $price != ''
                            && $topic != ''
                            && $accept != ''
                            && $article != ''
                            && $country != ''
                            && $kw_anchor != ''
                            && $seller_id != ''
                            && $language_excel != '')  {

                            if ($isValidURL) {

                                if (in_array($seller_id, $user_id_list)) {

                                    // check yes and no values
                                    if ( in_array($article, $yes_no_values) && in_array($accept, $yes_no_values) && in_array($kw_anchor, $yes_no_values) ) {

                                        if (preg_grep("/" . $language_excel . "/i", $language_name_list)) {

                                            if ($isCheckedTopic) {

                                                if (preg_grep("/" . $country . "/i", $country_name_list)) {

                                                    if (!$isCheckDuplicate) {

                                                        if (trim($url, " ") != '') {

                                                            $lang = $this->getLanguage($language_excel);
                                                            $count = $this->getCountry($country);
                                                            // $valid = $this->checkValid($url);

                                                            Publisher::create([
                                                                'user_id'      => $seller_id,
                                                                'language_id'  => $lang,
                                                                'continent_id' => $count->continent_id,
                                                                'country_id'   => $count->id,
                                                                'url'          => $url,
                                                                'ur'           => 0,
                                                                'dr'           => 0,
                                                                'backlinks'    => 0,
                                                                'ref_domain'   => 0,
                                                                'org_keywords' => 0,
                                                                'org_traffic'  => 0,
                                                                'price'        => preg_replace('/[^0-9.\-]/', '', $price),
                                                                'inc_article'  => ucwords(strtolower(trim($article, " "))),
                                                                // 'valid'        => $valid,
                                                                'valid'        => 'unchecked',
                                                                'casino_sites' => ucwords(strtolower(trim($accept, " "))),
                                                                'topic'        => $topic,
                                                                'kw_anchor'    => $kw_anchor,
                                                                'is_https'     => $this->httpClient->getProtocol($url) == 'https' ? 'yes' : 'no',
                                                            ]);
                                                        }
                                                    } else {
                                                        $file_message = " URL and Seller ID already exist, Check in line " . (intval($ctr) + 1);
                                                        $result = true;

                                                        array_push($existing_datas, [
                                                            'message' => $file_message
                                                        ]);
                                                    }
                                                } else {
                                                    $file_message = "No Country name of " . $country . $message . ". Check in line " . (intval($ctr) + 1);
                                                    $result = true;

                                                    array_push($existing_datas, [
                                                        'message' => $file_message
                                                    ]);
                                                }
                                            } else {
                                                $file_message = " No Topic name of " . $topic . $message . ". Check in line " . (intval($ctr) + 1);
                                                $result = true;

                                                array_push($existing_datas, [
                                                    'message' => $file_message
                                                ]);
                                            }
                                        } else {
                                            $file_message = "No Language name of " . $language_excel . $message . ". Check in line " . (intval($ctr) + 1);
                                            $result = true;

                                            array_push($existing_datas, [
                                                'message' => $file_message
                                            ]);
                                        }
                                    } else {
                                        $file_message = "Invalid data. Inc Article, Accept C&B and KW Anchor values must be yes/no only. Check line " . (intval($ctr) + 1);
                                        $result = true;

                                        array_push($existing_datas, [
                                            'message' => $file_message
                                        ]);
                                    }
                                } else {
                                    $file_message = "No Seller ID of " . $seller_id . $message . ". Check in line " . (intval($ctr) + 1);
                                    $result = true;

                                    array_push($existing_datas, [
                                        'message' => $file_message
                                    ]);
                                }
                            } else {
                                $file_message = "Invalid url format: " . $url . $message . ". Check in line " . (intval($ctr) + 1);
                                $result = true;

                                array_push($existing_datas, [
                                    'message' => $file_message
                                ]);
                            }
                        } else {
                            $file_message = "Invalid data. Columns cannot be empty. Check in line " . (intval($ctr) + 1);
                            $result = true;

                            array_push($existing_datas, [
                                'message' => $file_message
                            ]);
                        }
                    }
                }

                $ctr++;
            }
        }

        fclose($csv);

        return [
            "success" => $result,
            "message" => $message,
            "errors"  => [
                "file" => $file_message,
            ],
            "exist"   => $existing_datas,
        ];
    }

    private function checkTopic($topic) {
        $result = false;

        // list of topics
        $topic_list = $this->topic_list;

        // remove spaces
//        $topic = preg_replace('/\s+/', '', $topic);
        $topic = trim($topic);

        // checking if has comma
        if( strpos($topic, ',') !== false ) {

            $_topics = explode(',', $topic);
            $_topic_result = [];

            //checking if one of the topic is exist or not exist
            foreach($_topics as $_topic){
                if(preg_grep("/" . $_topic . "/i", $topic_list)){
                    array_push($_topic_result, true);
                } else {
                    array_push($_topic_result, false);
                }
            }

            if(!in_array(false, $_topic_result, true)) {
                $result = true;
            }

        } else { // no commas or single topic

            if(preg_grep("/" . $topic . "/i", $topic_list)){
                $result = true;
            }
        }

        return $result;
    }

    private function checkDuplicate($url, $seller_id)
    {
        $publisher = Publisher::where('url', 'like', '%' . $url . '%')->where('user_id', $seller_id);

        return $publisher->count() > 0;
    }

    // private function checkValid($url)
    // {
    //     $result = 'valid';
    //     $publisher = Publisher::where('url', 'like', '%' . $url . '%')->where('valid', 'valid')->count();
    //     if ($publisher > 0) {
    //         $result = 'unchecked';
    //     } else {
    //         $result = 'valid';
    //     }

    //     return $result;
    // }

    public function isValidURL($url){
        return preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/', $url);
    }

    private function checkUrlAndSeller($seller_id, $url)
    {
        $result = true;
        $publisher = Publisher::where('user_id', $seller_id)->where('url', 'like', '%' . $url . '%')->first();
        if (isset($publisher->id)) {
            $result = false;
        }

        return $result;
    }

    public function getLanguage($language)
    {
        $id = 5;
        $language = Language::where('name', 'like', '%' . $language . '%')->first();
        if ($language) {
            $id = $language->id;
        }

        return $id;
    }

    public function getCountry($country)
    {
        return Country::where('name', 'like', '%' . $country . '%')->first();
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

        foreach ($publisher as $key => $value) {
            $codeCombiURDR = $this->getCodeCombination($value->ur, $value->dr, 'value1');
            $codeCombiBlRD = $this->getCodeCombination($value->backlinks, $value->ref_domain, 'value2');
            $codeCombiOrgKW = $this->getCodeCombination($value->org_keywords, 0, 'value3');
            $codeCombiOrgT = $this->getCodeCombination($value->org_traffic, 0, 'value4');
            $combineALl = $codeCombiURDR . $codeCombiBlRD . $codeCombiOrgKW . $codeCombiOrgT;

            $price_list = Pricelist::where('code', strtoupper($combineALl))->first();

            $value['code_combination'] = $combineALl;
            $value['code_price'] = (isset($price_list->price) && !empty($price_list->price)) ? $price_list->price : 0;

            // Price Basis
            $result_1 = 0;
            $result_2 = 0;

            $price_basis = '-';
            if (!empty($value['code_price'])) {
                $var_a = floatVal($value->price);
                $var_b = floatVal($value['code_price']);

                $result_1 = number_format($var_b * 0.7, 2);
                $result_2 = number_format(($var_b * 0.1) + $var_b, 2);

                if ($result_1 != 0 && $result_2 != 0) {
                    if ($var_a <= $result_1) {
                        $price_basis = 'Good';
                    }

                    if ($var_a > $result_1 && $result_1 < $result_2) {
                        $price_basis = 'Average';
                    }

                    if ($var_a > $result_2) {
                        $price_basis = 'High';
                    }
                }
            }

            if ($value['code_price'] == 0 && $value->price > 0) {
                $price_basis = 'High';
            }

            Publisher::find($value->id)->update([
                'code_comb'       => $value['code_combination'],
                'code_price'      => $value['code_price'],
                'price_basis'     => $price_basis,
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
            ->with([
                'country' => function ($query) {
                    $query->select('id', 'name', 'code');
                }
            ])
            ->groupBy('language_id')
            ->distinct()
            ->get();

        $dataReturn = [];
        $sumTotal = 0;

        foreach ($publishers as $item) {
            $sumTotal += $item->total;
        }

        return [
            'total' => $sumTotal,
            'data'  => $publishers
        ];
    }

    private function initArrayReport(&$dataReturn, $key, $country)
    {
        $dataReturn[$key] = ['country' => $country];
        foreach ($this->statusList as $value) {
            $dataReturn[$key][$value] = 0;
        }
    }

    public function getDuplicateUrls()
    {
        return Publisher::select('url')->groupBy('url')->havingRaw('count(*) > 1')->get()->pluck('url');
    }

    public function getNonDuplicateUrls()
    {
        return Publisher::select('url')->groupBy('url')->havingRaw('count(*) < 2')->get()->pluck('url');
    }

    public function getPublisherByUrl($url, $relationships = [])
    {
        $query = Publisher::where('url', $url);

        foreach ($relationships as $relationship) {
            $query->with($relationship);
        }

        return $query->get();
    }
}
