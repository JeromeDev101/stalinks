<?php

namespace App\Repositories;

use App\Jobs\CrawlContactDomainJob;
use App\Jobs\SendEmailExtJob;
use App\Libs\Ahref;
use App\Models\Country;
use App\Models\ExtDomain;
use App\Models\Publisher;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\CrawlContactRepositoryInterface;
use App\Repositories\Contracts\ExtDomainRepositoryInterface;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;


class ExtDomainRepository extends BaseRepository implements ExtDomainRepositoryInterface
{

    protected $model;

    protected $crawler;

    protected $statusList;

    /**
     * ExtDomainRepository construct
     *
     * @param  mixed $model
     * @param  CrawlContactRepositoryInterface $crawler
     * @return void
     */
    public function __construct(ExtDomain $model, CrawlContactRepositoryInterface $crawler)
    {
        parent::__construct($model);
        $this->crawler = $crawler;
        $this->statusList = array(
            config('constant.EXT_STATUS_NEW') => 'New',
            config('constant.EXT_STATUS_CRAWL_FAILED') => 'CrawlFailed',
            config('constant.EXT_STATUS_CONTACTS_NULL') => 'ContactsNull',
            config('constant.EXT_STATUS_GOT_CONTACTS') => 'GotContacts',
            config('constant.EXT_STATUS_AHREAFED') => 'Ahreafed',
            config('constant.EXT_STATUS_CONTACTED') => 'Contacted',
            config('constant.EXT_STATUS_NO_ANSWER') => 'NoAnswer',
            config('constant.EXT_STATUS_REFUSED') => 'Refused',
            config('constant.EXT_STATUS_IN_TOUCHED') => 'InTouched',
            config('constant.EXT_STATUS_UNDEFINED') => 'Undefined',
            config('constant.EXT_STATUS_QUALIFIED') => 'Qualified',
            config('constant.UNQUALIFIED') => 'Unqualified',
        );
    }

    public function importExcel($file)
    {

        // Limit the CSV rows to 1000 only
        $fp = file($file['file']->getPathName());
        if( count($fp) > 1001) { // 1001 included the number of header
            return [
                "success" => false,
                "message" => 'Invalid number of rows',
                "errors" => [
                    "file" => 'CSV is exceeded to 1000 rows',
                ],
                "exist" => [],
            ];
        }

        // $status = $file['status'];
        // $language = $file['language'];
        $csv_file = $file['file'];
        $country_name_list = Country::pluck('name')->toArray();
        $status_list = ['New', 'CrawlFailed', 'ContactNull', 'GotContacts', 'GotEmail', 'Contacted', 'ContactedViaForm', 'NoAnswer', 'Refused', 'InTouched', 'Unqualified', 'Qualified'];

        $result = true;
        $message = '';
        $file_message = '';

        $id = Auth::user()->id;
        $csv = fopen($csv_file, 'r');
        $ctr = 0;

        $datas = [];
        $existing_datas = [];
        while ( ($line = fgetcsv($csv) ) !== FALSE) {
            if(count($line) > 4 || count($line) < 4){
                $message = "Please check the header: Domain, Status, Country and Email only.";
                $file_message = "Invalid Header format. ".$message;
                $result = false;
                break;
            }

            if( $ctr > 0 ){
                $url = trim_special_characters($line[0]);
                $status = trim_special_characters($line[1]);
                $country = trim_special_characters($line[2]);
                $email = trim_special_characters($line[3]);

                // remove http
                $url = remove_http($url);

                // remove space
                $url = trim($url, " ");

                // remove /
                $url = rtrim($url,"/");

                $isExistDomain = $this->checkDomain($url);
//                $isExistEmail = $this->checkEmail($email);

                if ($email == 'null' || $email == '') {
                    $email = null;
                    $isExistEmail = false;
                } else {
                    $isExistEmail = $this->checkEmail($email);
                }

                if( trim($url, " ") != '' ){

                    if (!$isExistEmail) {

                        if (preg_grep("/".$country."/i", $country_name_list)){

                            if (preg_grep("/".$status."/i", $status_list)){

                                if ($isExistDomain){

                                    $lang = $this->getCountry($country);
                                    $status = $this->getStatusCode($status);

                                    ExtDomain::create([
                                        'user_id' => $id,
                                        'domain' => $url,
                                        'country_id' => $lang,
                                        'ahrefs_rank' => 0,
                                        'no_backlinks' => 0,
                                        'url_rating' => 0,
                                        'domain_rating' => 0,
                                        'organic_keywords' => '',
                                        'organic_traffic' => '',
                                        'alexa_rank' => 0,
                                        'ref_domains' => 0,
                                        'status' => $status,
                                        'email' => $email,
                                        'from' => 'Prospect'
                                    ]);

                                }else{

                                    array_push($existing_datas, [
                                        'message' => 'Existing Domain '.$url. ". Check in line ". (intval($ctr) + 1),
                                    ]);
                                }

                            }else{
                                array_push($existing_datas, [
                                    'message' => "No Status name of ". $status . $message . ". Check in line ". (intval($ctr) + 1),
                                ]);
                            }

                        }else{
                            array_push($existing_datas, [
                                'message' => "No Country name of ". $country . $message . ". Check in line ". (intval($ctr) + 1),
                            ]);
                        }

                    } else {
                        array_push($existing_datas, [
                            'message' => 'Existing Email '.$email. ". Check in line ". (intval($ctr) + 1),
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

    private function getStatusCode($status){
        $result = 0;

        $status_list = [
            0 => 'New',
            10 => 'CrawlFailed',
            20 => 'ContactNull',
            30 => 'GotContacts',
//            40 => 'Ahrefed',
            50 => 'Contacted',
            55 => 'NoAnswer',
            60 => 'Refused',
            70 => 'InTouched',
            90 => 'Unqualified',
            100 => 'Qualified',
            110 => 'GotEmail',
            120 => 'ContactedViaForm'
        ];

        if( array_search(strtolower($status), array_map('strtolower', $status_list)) ){
            $result = array_search(strtolower($status), array_map('strtolower', $status_list));
        }

        return $result;
    }

    private function checkDomain($domain) {
        $result = true;

        $domain = remove_http($domain);

//        $checkExtDomain = ExtDomain::where('domain', 'like', '%'.$domain.'%');
//        $checkPublisher = Publisher::where('url', 'like', '%'.$domain.'%');

        $checkExtDomain = ExtDomain::where('domain', $domain);
        $checkPublisher = Publisher::where('url', $domain);

        if( $checkExtDomain->count() > 0 || $checkPublisher->count() > 0 ){
            $result = false;
        }

        return $result;
    }

    private function checkEmail($email) {
        return ExtDomain::whereRaw("find_in_set('$email',replace(email,'|',','))")->count() !== 0;
    }

    private function getCountry($country){
        $id = 5;
        $country = Country::where('name', 'like', '%'.$country.'%')->first();
        if( $country ){
            $id = $country->id;
        }
        return $id;
    }

    public function fillterExtDomain($status, $countryIds, $countryIdsInt, $extDomainAdditionIds = [])
    {
        $data = $this->model->selectRaw('ext_domains.country_id, ext_domains.status, count(DISTINCT(ext_domains.id)) as total')
            ->leftJoin('backlinks','backlinks.ext_domain_id', '=', 'ext_domains.id')
            //->leftJoin('int_domains','backlinks.int_domain_id', '=', 'int_domains.id')
            ->whereIn('ext_domains.country_id', $countryIds)
           // ->orWhereIn('int_domains.country_id', $countryIdsInt)
            ->orWhereIn('ext_domains.id', $extDomainAdditionIds)
            ->with(['country' => function($query) {
                $query->select('id', 'name', 'code');
            }])
            ->groupBy('ext_domains.country_id', 'ext_domains.status')
            ->distinct()
            ->get();

        $dataReturn = [];
        $sumTotal = 0;

        foreach($data as $item) {
            $sumTotal += $item->total;
            $key = $item->country_id;
            if (!isset($this->statusList[$item->status])) {
                $item->status = config('constant.EXT_STATUS_UNDEFINED');
            }
            $statusName = $this->statusList[$item->status];

            if (!isset($dataReturn[$key])) {
                $this->initArrayReport($dataReturn, $key, $item->country);
            }

            $dataReturn[$key][$statusName] += $item->total;
        }

        return [
            'total' => $sumTotal,
            'data' => Arr::divide($dataReturn)[1]
        ];
    }

    private function initArrayReport(&$dataReturn, $key, $country) {
        $dataReturn[$key] = ['country' => $country];
        foreach($this->statusList as $value) {
            $dataReturn[$key][$value] = 0;
        }
    }

    public function importAlexaSites($data, $total, $countryCode, $start, $count)
    {
        $dataReturn = [
            'extDomains' => [],
            'new' => 0,
            'existed' => 0,
        ];

        $newCount = 0;
        $existedCount = 0;

        $country = Country::where('code', $countryCode)->first();

        if (!$country) {
            return $dataReturn;
        }

        $listIdNeedCrawlContact = [];

        if( is_array($data) ){
            foreach($data as $domain) {
                $extDomain = ExtDomain::where('domain', $domain['DataUrl'])->first();

                if ($extDomain) {
                    $extDomain->alexa_rank = $domain['Country']['Rank'];
                    $extDomain->save();
                    $existedCount++;
                    $extDomain->country = $country;
                    $dataReturn['extDomains'][] = $extDomain;
                    $listIdNeedCrawlContact[] = $extDomain->id;
                    continue;
                }

                $newExt = ExtDomain::firstOrCreate([
                    'domain' => $domain['DataUrl'],
                    'alexa_rank' => $domain['Country']['Rank'],
                    'country_id' => $country->id,
                    'ahrefs_rank' => 0,
                    'no_backlinks' => 0,
                    'url_rating' => 0,
                    'domain_rating' => 0,
                    'ref_domains' => 0,
                    'organic_keywords' => '',
                    'organic_traffic' => '',
                    'email' => '',
                    'phone' => '',
                    'user_id' => Auth::user()->id,
                    'facebook' => '',
                    'status' => config('constant.EXT_STATUS_NEW'),
                ]);

                $newExt->country = $country;
                $listIdNeedCrawlContact[] = $newExt->id;


                $newCount++;
                $dataReturn['extDomains'][] = $newExt;
            }
        }

        $this->addLog(config('constant.ACTION_ALEXA'), ['country_code' => $countryCode, 'start' => $start, 'count' => $count]);

        $dataReturn['new'] = $newCount;
        $dataReturn['existed'] = $existedCount;
        $dataReturn['total'] = $total;
        $this->crawlContact($listIdNeedCrawlContact, true);
        return $dataReturn;
    }

    public function getContacts($listIdExtDomains)
    {
        // TODO: Implement getContacts() method.
    }

    public function paginate($input)
    {
        $query = $this->model->newQuery();
        $page = 0;
        $perPage = 50;

        $query = $query->select(
            'ext_domains.*',
            'countries.name AS country_name',
            'A.name',
            'A.username as user_name',
            'A.isOurs'
        );

        // Employee Filter
        if (isset($input['employee_id']) && !empty($input['employee_id'])) {
            if (is_array($input['employee_id'])) {
                $query->where(function ($q) use ($input) {
                    foreach ($input['employee_id'] as $name) {
                        if ($name == 'N/A') {
                            $q->orWhere('user_id', null);
                        } else {
                            $user = User::where('username', 'like', '%' . $name . '%')->first();

                            $q->orWhere('user_id', $user->id);
                        }
                    }
                });
            }
        }

        // Set page
        if (isset($input['page'])) {
            $page = $input['page'];
        }

        // Set per_page
        if (isset($input['per_page'])) {
            $perPage = $input['per_page'];
        }

        // Email Filter
        if (isset($input['email'])) {
            $query->where('ext_domains.email', 'like', '%' . $input['email'] . '%');
        }

        // Country Filter
        if (isset($input['country_id']) && $input['country_id'] != '0') {
            if (is_array($input['country_id'])) {
                $countryIds = Country::whereIn('name', $input['country_id'])->get()->pluck('id');
                $query->whereIn('country_id', $countryIds);
            } else {
                $countryId = Country::where('name', $input['country_id'])->first()->id;
                $query->where('country_id', $countryId);
            }
        }

        // Email Required filter
        if (isset($input['required_email']) && $input['required_email'] > 0) {
            $query->where('ext_domains.email', '!=', '');
        }

        // Domain Filter
        if (isset($input['domain'])) {
            $query->where('domain', 'like', '%' . $input['domain'] . '%');
        }

        // From Filter
        if (isset($input['from'])) {
            $query->where('from', $input['from']);
        }

        // Status Filter
        if (isset($input['status']) && !empty($input['status']) && $input['status'] != '-1') {
            if (is_array($input['status'])) {
                $query->whereIn('ext_domains.status', $input['status']);
            } else {
                $query->where('ext_domains.status', $input['status']);
            }
        }

        // Alexa Rank Filter
        if (isset($input['alexa_rank_from']) && !empty($input['alexa_rank_from']) && isset($input['alexa_rank_to']) && !empty($input['alexa_rank_to']) ) {
            $query->whereBetween('alexa_rank',[$input['alexa_rank_from'], $input['alexa_rank_to']]);
        }

        // Domain Zone
        if (isset($input['domain_zone']) && !empty($input['domain_zone'])) {
            if (is_array($input['domain_zone'])) {

                $regs = implode(",", $input['domain_zone']);
                $regs = str_replace('.', '', $regs);
                $regs = explode(",", $regs);

                $query->whereIn(DB::raw("REPLACE(REPLACE(SUBSTRING_INDEX(domain, '.', -1),' ',''),'/','')"), $regs);

            } else {

                $regs = str_replace('.', '', $input['domain_zone']);

                $query->whereRaw("REPLACE(REPLACE(SUBSTRING_INDEX(domain, '.', -1),' ',''),'/','') = '$regs'");
            }
        }

        // Date upload filter
        $input['alexa_date_upload'] = \GuzzleHttp\json_decode($input['alexa_date_upload'], true);

        if (isset($input['alexa_date_upload']) && $input['alexa_date_upload']['startDate'] != null && $input['alexa_date_upload']['endDate'] != null) {
            $query->where('ext_domains.created_at', '>=', Carbon::create($input['alexa_date_upload']['startDate'])->format('Y-m-d'));
            $query->where('ext_domains.created_at', '<=', Carbon::create($input['alexa_date_upload']['endDate'])->format('Y-m-d'));
        }

        // Include relationships
        $query->with(['country' => function($query) {
            $query->select(['id', 'name', 'code']);
        }, 'users' => function($query) {
            $query->select('id','username', 'status');
        }]);

        // joins

        $query = $query->leftJoin('users as A', 'ext_domains.user_id', '=', 'A.id')
            ->leftJoin('countries', 'ext_domains.country_id', '=', 'countries.id');

        if (isset($input['adv_sort']) && !empty($input['adv_sort'])) {
            foreach ($input['adv_sort'] as &$sort) {
                $sort = \GuzzleHttp\json_decode($sort);
                $query = $query->orderByRaw("$sort->column $sort->sort");
            }
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    private function getExtDomainFromCountryInt($allExtFilter, $countriesId, $countriesIdInt, $countriesExceptIds) {
        $extDomainFromIntCountry = $this->model->selectRaw('ext_domains.id')
            ->leftJoin('backlinks','backlinks.ext_domain_id', '=', 'ext_domains.id')
            ->leftJoin('int_domains','backlinks.int_domain_id', '=', 'int_domains.id')
            ->distinct();

        $dataAddExt = $extDomainFromIntCountry->where(function($query) use ($allExtFilter, $countriesId, $countriesIdInt, $countriesExceptIds) {
            $query->whereIn('int_domains.country_id', $countriesIdInt);

            if ($allExtFilter === false) {
                $query->where(function($queryIn) use ($countriesId, $countriesExceptIds) {
                    $queryIn->whereIn('ext_domains.country_id', $countriesId)
                        ->orWhereIn('ext_domains.country_id', $countriesExceptIds);
                });
            }
        })->get();

        return $dataAddExt;
    }

    public function updateData(array $attributes = [], array $countryIdsFilter)
    {
        // if (Auth::user()->isAdmin()) {
        //     $extModel = $this->model->where('id', $attributes['id']);
        //     if ($extModel->count() > 0) {
        //         $this->update($extModel->first(), $attributes);
        //         return true;
        //     }

        //     return false;
        // }

        // $extModel = $this->model->whereIn('country_id', $countryIdsFilter)->where('id', $attributes['id']);
        $extModel = $this->model->where('id', $attributes['id']);
        if ($extModel->count() > 0) {
            $this->update($extModel->first(), $attributes);
            return true;
        }

        return false;
    }


    public function updateStatus($listIds, $newStatus, $countryIdsFilter)
    {
        if (Auth::user()->isAdmin()) {
            $this->model->whereIn('id', $listIds)->update(['status' => $newStatus]);
        }

        $this->model->whereIn('country_id', $countryIdsFilter)->whereIn('id', $listIds)->update(['status' => $newStatus]);
    }

    public function create(array $attributes)
    {
        $newExt = parent::create($attributes); // TODO: Change the autogenerated stub

        // if ($newExt->status === config('constant.EXT_STATUS_NEW')) {
        //     return $this->crawlContact([$newExt->id], false)->first();
        // }

        return $newExt;
    }

    public function crawlContact($listIds, $pushToQueue)
    {
//        $extDomains = $this->model->whereIn('id', $listIds)
//           ->where(function($query) {
//                $query->where('email', '')->orWhereNull('email');
//           })->where(function($query) {
//               $query->where('phone', '')->orWhereNull('phone');
//           })->where(function($query) {
//               $query->where('facebook', '')->orWhereNull('facebook');
//           })->get();

        $extDomains = $this->model->whereIn('id', $listIds)->get();

        if ($extDomains->count() == 0) {
            if ($pushToQueue  === true) {
                return [];
            } else {
                return $this->model->whereIn('id', $listIds)->with('country')->get();
            }
        }

        if ($pushToQueue === true) {
            $maxWorkerCount = config('crawler.max_process');
            foreach($extDomains->chunk($maxWorkerCount) as $chunk) {
                $job = (new CrawlContactDomainJob($chunk));
                dispatch($job)->onQueue('crawl');
            }

            return [];
        }

        $this->crawler->crawls($extDomains);
        $resultCrawled = $this->model
            ->whereIn('id', $listIds)
            ->with('country')
            ->with('users:id,username,status')
            ->get();
        return $resultCrawled;
    }

    /**
     * Get ahrefs with promise for request async concurrency
     * @param array $listIds
     * @param array $configs
     * @return array
     */
    public function getAhrefs($listIds, $configs)
    {
//        $statusGotContact = config('constant.EXT_STATUS_GOT_CONTACTS');
//        $extDomains = $this->model->whereIn('id', $listIds)
//            ->where('status', $statusGotContact)->get();

        $extDomains = $this->model->whereIn('id', $listIds)->get();

        if ($extDomains->count() == 0) {
            return [];
        }

        $ahrefsInstant = new Ahref($configs);
        $data = $ahrefsInstant->getAhrefsAsync($extDomains);
        return $data;
    }

}
