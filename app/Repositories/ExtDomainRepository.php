<?php

namespace App\Repositories;

use App\Jobs\CrawlContactDomainJob;
use App\Jobs\SendEmailExtJob;
use App\Libs\Ahref;
use App\Models\Country;
use App\Models\ExtDomain;
use App\Models\Publisher;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\CrawlContactRepositoryInterface;
use App\Repositories\Contracts\ExtDomainRepositoryInterface;
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
        // $status = $file['status'];
        // $language = $file['language'];
        $csv_file = $file['file'];
        $country_name_list = Country::pluck('name')->toArray();
        $status_list = ['New', 'CrawlFailed', 'ContactNull', 'GotContacts', 'Ahrefed', 'Contacted', 'NoAnswer', 'Refused', 'InTouched', 'Unqualified', 'Qualified'];

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
                $url = $line[0];
                $status = $line[1];
                $country = $line[2];
                $email = $line[3];

                $isExistDomain = $this->checkDomain($url);

                if( trim($url, " ") != '' ){

                    if (preg_grep("/".$country."/i", $country_name_list)){

                        if (preg_grep("/".$status."/i", $status_list)){

                            if ($isExistDomain){

                                $lang = $this->getCountry($country);
                                $status = $this->getStatusCode($status);

                                ExtDomain::create([
                                    'user_id' => $id,
                                    'domain' => $this->remove_http($url),
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

    private function remove_http($url) {
        $disallowed = array('http://', 'https://', 'www.', '/');
        foreach($disallowed as $d) {
           if(strpos($url, $d) === 0) {
              return str_replace($d, '', $url);
           }
        }
        return $url;
    }

    private function getStatusCode($status){
        $result = 0;

        $status_list = [
            0 => 'New',
            10 => 'CrawlFailed',
            20 => 'ContactNull',
            30 => 'GotContacts',
            40 => 'Ahrefed',
            50 => 'Contacted',
            55 => 'NoAnswer',
            60 => 'Refused',
            70 => 'InTouched',
            90 => 'Unqualified',
            100 => 'Qualified'
        ];

        if( array_search(strtolower($status), array_map('strtolower', $status_list)) ){
            $result = array_search(strtolower($status), array_map('strtolower', $status_list));
        }

        return $result;
    }

    private function checkDomain($domain) {
        $result = true;

        $checkExtDomain = ExtDomain::where('domain', 'like', '%'.$domain.'%');
        $checkPublisher = Publisher::where('url', 'like', '%'.$domain.'%');

        if( $checkExtDomain->count() > 0 || $checkPublisher->count() > 0 ){
            $result = false;
        }

        return $result;
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

    public function paginate($page, $perPage, $filters, $countriesId, $countriesIdInt, $countriesExceptIds, $allExtFilter, $sort, $extDomainAdditionIds = [])
    {
        $queryBuilder = $this->buildSimpleFilterQuery($filters);
        $dataAddExt = $this->getExtDomainFromCountryInt($allExtFilter, $countriesId, $countriesIdInt, $countriesExceptIds);

        foreach($dataAddExt as $item) {
            $extDomainAdditionIds[] = $item->id;
        }

        $queryBuilder = $queryBuilder->orWhere(function($query) use ($allExtFilter, $filters, $countriesId, $countriesExceptIds, $extDomainAdditionIds) {
            $query->whereIn('id', $extDomainAdditionIds);
            $this->buildSimpleFilterQueryBuilder($query, $filters, ['country_id']);

            if ($allExtFilter === false) {
                $query->where(function($queryIn) use ($countriesId, $countriesExceptIds, $filters) {
                    $this->buildSimpleFilterQueryBuilder($queryIn, $filters['other']);
                });
            }
        })
        ->with(['country' => function($query) {
            $query->select(['id', 'name', 'code']);
        }, 'backlinks' => function($query) {
            $query->select('ext_domain_id', 'price');
        }, 'users' => function($query) {
            $query->select('id','username');
        }]);

        if ($sort[0] === 'ext_domains.total_spent') {
            $queryBuilder->select("*",
                DB::raw('(SELECT SUM(price) FROM backlinks WHERE ext_domains.id = backlinks.ext_domain_id) as sum_price'))
                ->orderBy('sum_price', $sort[1]);
        } else {
            $queryBuilder->orderBy($sort[0], $sort[1]);
        }

        $data = $queryBuilder->paginate($perPage, ['*'], 'page', $page);

        foreach($data as &$item) {
            $item['total_spent'] = collect($item['backlinks'])->sum('price');
            unset($item['backlinks']);
        }

        return $data;
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
        if (Auth::user()->isAdmin()) {
            $extModel = $this->model->where('id', $attributes['id']);
            if ($extModel->count() > 0) {
                $this->update($extModel->first(), $attributes);
                return true;
            }

            return false;
        }

        $extModel = $this->model->whereIn('country_id', $countryIdsFilter)->where('id', $attributes['id']);
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
        if ($newExt->status === config('constant.EXT_STATUS_NEW')) {
            return $this->crawlContact([$newExt->id], false)->first();
        }

        return $newExt;
    }

    public function crawlContact($listIds, $pushToQueue)
    {
        $extDomains = $this->model->whereIn('id', $listIds)
           ->where(function($query) {
                $query->where('email', '')->orWhereNull('email');
           })->where(function($query) {
               $query->where('phone', '')->orWhereNull('phone');
           })->where(function($query) {
               $query->where('facebook', '')->orWhereNull('facebook');
           })->get();

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
                dispatch($job);
            }

            return [];
        }

        $this->crawler->crawls($extDomains);
        $resultCrawled = $this->model->whereIn('id', $listIds)->with('country')->orderBy('id', 'desc')->get();
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
        $statusGotContact = config('constant.EXT_STATUS_GOT_CONTACTS');
        $extDomains = $this->model->whereIn('id', $listIds)
            ->where('status', $statusGotContact)->get();

        if ($extDomains->count() == 0) {
            return [];
        }

        $ahrefsInstant = new Ahref($configs);
        $data = $ahrefsInstant->getAhrefsAsync($extDomains);
        return $data;
    }

}
