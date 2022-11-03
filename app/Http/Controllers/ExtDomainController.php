<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailExtJob;
use App\Libs\Alexa;
use App\Repositories\Contracts\ConfigRepositoryInterface;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Repositories\Contracts\CrawlContactRepositoryInterface;
use App\Rules\EmailPipe;
use App\Rules\ExtListEmail;
use App\Rules\ExtListLink;
use App\Rules\ExtListPhone;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Repositories\Contracts\ExtDomainRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use App\Models\Publisher;
use App\Models\User;
use App\Models\Country;
use App\Models\ExtDomain;
use App\Models\Config;
use League\OAuth2\Server\RequestEvent;
use PharIo\Manifest\Email;

class ExtDomainController extends Controller
{
    /**
     * @var ExtDomainRepositoryInterface
     */
    private $extDomainRepository;

    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepository;

    /**
     * @var ConfigRepositoryInterface
     */
    private $configRepository;

    /**
     * @var CrawlContactRepositoryInterface
     */
    private $crawlContactRepository;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * ExtDomainController constructor.
     *
     * @param ExtDomainRepositoryInterface $extDomainRepository
     * @param CountryRepositoryInterface $countryRepository
     * @param CrawlContactRepositoryInterface $crawlContactRepository
     * @param UserService $userService
     */

    private $validStatus;

    public function __construct(ExtDomainRepositoryInterface $extDomainRepository,
                                CountryRepositoryInterface $countryRepository,
                                CrawlContactRepositoryInterface $crawlContactRepository,
                                ConfigRepositoryInterface $configRepository,
                                UserService $userService)
    {
        $this->extDomainRepository = $extDomainRepository;
        $this->configRepository = $configRepository;
        $this->countryRepository = $countryRepository;
        $this->userService =  $userService;
        $tempStatus = array(
            config('constant.EXT_STATUS_NEW'),
            config('constant.EXT_STATUS_CONTACTS_NULL'),
            config('constant.EXT_STATUS_CRAWL_FAILED'),
            config('constant.EXT_STATUS_GOT_CONTACTS'),
            config('constant.EXT_STATUS_AHREAFED'),
            config('constant.EXT_STATUS_CONTACTED'),
            config('constant.EXT_STATUS_REFUSED'),
            config('constant.EXT_STATUS_IN_TOUCHED'),
            config('constant.UNQUALIFIED'),
            config('constant.EXT_STATUS_QUALIFIED')
        );

        $this->validStatus = [];
        foreach($tempStatus as $value) {
            $this->validStatus[$value] = $value;
        }
    }

    public function reports(Request $request)
    {
        $input = $request->all();
        $userId = $this->userService->checkUserId($input['employee_id']);
        if (isset($input['country_id']) && $input['country_id'] > 0) {
            $countryIds = explode(",", $input['country_id']);
            $countryIds = $this->countryRepository->validCountryIdList($countryIds, $userId, true);
        } else {
            $countryIds = $this->countryRepository->getListCountriesAccess($userId, true);
        }

        $countryIdsInt = $this->countryRepository->getListCountriesAccess($userId);

        if ($input['status'] === 0) {
            $input['status'] = $input['status_of_user'];
        }

        if (!is_array($input['country_id'])) {
            $input['country_id'] = explode(",", $input['country_id']);
        }

        if (!is_array($input['status'])) {
            $input['status'] = explode(",", $input['status']);
        }

        $extDomainIds = $this->userService->findExtDomainIdsFromInt($userId);
        $total = $this->extDomainRepository->fillterExtDomain($input['status'], $countryIds, $countryIdsInt, $extDomainIds);

        return response()->json($total);
    }

    /**
     * @params $request Request form: country_id, start, count
     * @return \Illuminate\Database\Eloquent\Collection list ext domain get from alexa
     */
    public function getAlexaLink(Request $request) {
        $input = $request->all();
        $countryCode = "VN";
        $start = 0;
        $count = 10;

        Validator::make($input, [
            'country_id' => 'required|integer|not_in:0',
        ])->validate();

        if (isset($input['country_id'])) {
            $country = $this->countryRepository->find([['id', $input['country_id']]]);

            if ($country) {
                $country = $country->first();
                if (!$country) {
                    return response()->json([]);
                }

                $countryCode = $country->code;
            }
        }
        if (isset($input['start'])) {
            $start = $input['start'];
        }

        if (isset($input['count'])) {
            $count = $input['count'];
        }

        // save consume of alexa base on count
        $alexa = Config::where('code', 'alexa_consume')->first();
        $value = intVal($alexa->value);
        $consume = $value + intVal($count);
        if($consume > 40000) {
            $remaining = 40000 - $value;
            return response()->json(['remaining'=>$remaining], 400);
        }

        $alexa->update(['value' => $consume]);

        // Comment for save money :D
        $alexaLib = $this->getAlexaInstance($countryCode, $start, $count);
        $newData = $alexaLib->getTopSites($this->extDomainRepository);

        $status = true;
        if($newData['total'] < $start || !isset($newData['total'])) {
            $status = false;
        }

        $newData['status'] = $status;

        return response()->json($newData);
    }

    private function getAlexaInstance($countryCode, $start, $count) {
        $alexa = new Alexa($countryCode, $start, $count);
        $configs = $this->configRepository->getConfigs('alexa');
        $alexa->setConfigs($configs);

        return $alexa;
    }

    public function getList(Request $request) {
        $input = $request->all();

        $data = $this->extDomainRepository->paginate($input);

        return response()->json($this->addPaginationRaw($data));
    }

    public function getListTotals (Request $request)
    {
        $input = $request->all();


        $totals = DB::table('ext_domains')
            ->selectRaw("count(*) as Total")
            ->selectRaw("count(case when status = 0 then 1 end) as New")
            ->selectRaw("count(case when status = 10 then 1 end) as CrawlFailed")
            ->selectRaw("count(case when status = 50 then 1 end) as Contacted")
            ->selectRaw("count(case when status = 120 then 1 end) as ContactedViaForm")
            ->selectRaw("count(case when status = 20 then 1 end) as ContactNull")
            ->selectRaw("count(case when status = 30 then 1 end) as GotContacts")
            ->selectRaw("count(case when status = 110 then 1 end) as GotEmail")
            ->selectRaw("count(case when status = 55 then 1 end) as NoAnswer")
            ->selectRaw("count(case when status = 60 then 1 end) as Refused")
            ->selectRaw("count(case when status = 70 then 1 end) as InTouched")
            ->selectRaw("count(case when status = 90 then 1 end) as Unqualified")
            ->selectRaw("count(case when status = 100 then 1 end) as Qualified")
            ->leftJoin('countries', 'ext_domains.country_id', '=', 'countries.id');

//        // Employee Filter
//        if (isset($input['employee_id']) && !empty($input['employee_id'])) {
//            if (is_array($input['employee_id'])) {
//                $totals = $totals->where(function ($q) use ($input) {
//                    foreach ($input['employee_id'] as $name) {
//                        if ($name == 'N/A') {
//                            $q->orWhere('user_id', null);
//                        } else {
//                            $user = User::where('username', 'like', '%' . $name . '%')->first();
//
//                            $q->orWhere('user_id', $user->id);
//                        }
//                    }
//                });
//            }
//        }
//
//        // Email Filter
//        if (isset($input['email'])) {
//            $totals = $totals->where('ext_domains.email', 'like', '%' . $input['email'] . '%');
//        }
//
//        // continent filter
//        if (isset($input['continent_id']) && !empty($input['continent_id'])) {
//            $totals = $totals->where(function ($query) use ($input) {
//                if (($key = array_search(0, $input['continent_id'])) !== false) {
//                    unset($input['continent_id'][$key]);
//                    $query->orWhere(function ($subs) {
//                        $subs->orWhere('countries.continent_id', null);
//                    });
//                }
//
//                if (!empty($input['continent_id'])) {
//                    $query->orWhereIn('countries.continent_id', $input['continent_id']);
//                }
//            });
//        }
//
//        // Country Filter
//        if (isset($input['country_id']) && $input['country_id'] != '0') {
//            if (is_array($input['country_id'])) {
//                $totals = $totals->whereIn('country_id', $input['country_id']);
//            } else {
//                $totals = $totals->where('country_id', $input['country_id']);
//            }
//        }
//
//        // Email Required filter
//        if (isset($input['required_email']) && $input['required_email'] > 0) {
//            $totals = $totals->where('ext_domains.email', '!=', '');
//        }
//
//        // Domain Filter
//        if (isset($input['domain'])) {
//            $totals = $totals->where('domain', 'like', '%' . $input['domain'] . '%');
//        }
//
//        // From Filter
//        if (isset($input['from'])) {
//            if (is_array($input['from'])) {
//                $totals = $totals->whereIn('from', $input['from']);
//            } else {
//                $totals = $totals->where('from', $input['from']);
//            }
//        }
//
//        // Status Filter
//        if (isset($input['status']) && !empty($input['status']) && $input['status'] != '-1') {
//            if (is_array($input['status'])) {
//                $totals = $totals->whereIn('ext_domains.status', $input['status']);
//            } else {
//                $totals = $totals->where('ext_domains.status', $input['status']);
//            }
//        }
//
//        // Alexa Rank Filter
//        if (isset($input['alexa_rank_from']) && !empty($input['alexa_rank_from']) && isset($input['alexa_rank_to']) && !empty($input['alexa_rank_to']) ) {
//            $totals = $totals->whereBetween('alexa_rank',[$input['alexa_rank_from'], $input['alexa_rank_to']]);
//        }
//
//        // Domain Zone
//        if (isset($input['domain_zone']) && !empty($input['domain_zone'])) {
//            if (is_array($input['domain_zone'])) {
//
//                $regs = implode(",", $input['domain_zone']);
//                $regs = str_replace('.', '', $regs);
//                $regs = explode(",", $regs);
//
//                $totals = $totals->whereIn(DB::raw("REPLACE(REPLACE(SUBSTRING_INDEX(domain, '.', -1),' ',''),'/','')"), $regs);
//
//            } else {
//
//                $regs = str_replace('.', '', $input['domain_zone']);
//
//                $totals = $totals->whereRaw("REPLACE(REPLACE(SUBSTRING_INDEX(domain, '.', -1),' ',''),'/','') = '$regs'");
//            }
//        }
//
//        // Date upload filter
//        $input['alexa_date_upload'] = \GuzzleHttp\json_decode($input['alexa_date_upload'], true);
//
//        if (isset($input['alexa_date_upload']) && $input['alexa_date_upload']['startDate'] != null && $input['alexa_date_upload']['endDate'] != null) {
//            $totals = $totals->whereDate('ext_domains.created_at', '>=', Carbon::create($input['alexa_date_upload']['startDate'])->format('Y-m-d'));
//            $totals = $totals->whereDate('ext_domains.created_at', '<=', Carbon::create($input['alexa_date_upload']['endDate'])->format('Y-m-d'));
//        }

        $totals = $totals->whereNull('deleted_at')->first();

        return response()->json($totals);
    }

    // private function giveAccessToNonEmployees($userId) {
    //     $user = User::find($userId);
    //     $result = false;
    //     if( isset( $user->isOurs ) ){
    //         if( $user->isOurs == 1 ){
    //             $result = Country::pluck('id')->toArray();
    //         }
    //     }
    //     return $result;
    // }

    public function store(Request $request) {
        $id = Auth::user()->id;
        $input = $request->only(['info','skype','domain', 'country_id', 'alexa_rank',
            'ahrefs_rank', 'no_backlinks', 'url_rating', 'domain_rating', 'ref_domains', 'organic_keywords', 'organic_traffic', 'email',
            'facebook', 'phone']);

        foreach($input as $key => $value) {
            if (!isset($input[$key])) $input[$key] = '';
        }

        $input['from'] = 'Manual';

        // handle email
        $input['email'] = array_column($input['email'], 'text');

        //  $customMessages = [];

        //  foreach ($input['email'] as $key => $value) {
        //     $customMessages['email.' . $key . '.unique'] = $value . ' is already taken.';
        //     $customMessages['email.' . $key . '.email'] = $value . ' is not a valid email.';
        //  }

        $newStatus = 0;

        Validator::make($input, [
            'domain' => 'required|max:255',
//            'email.*' => 'email|unique:ext_domains,email',
            // 'email' => 'array|max:10',
            // 'email.*' => ['email', new EmailPipe('add')],
            // 'email' => 'email',
            'country_id' => 'required|integer|not_in:0',
            'alexa_rank' => 'required|integer|gte:0',
            'ahrefs_rank' => 'required|integer|gte:0',
            'no_backlinks' => 'required|integer|gte:0',
            'url_rating' => 'required|integer|gte:0',
            'domain_rating' => 'required|integer|gte:0',
            'ref_domains' => 'required|integer|gte:0'
        ])->validate();

        $url_remove_http = remove_http($input['domain']);

//        $checkExtDomain = ExtDomain::where('domain', 'like', '%'.$url_remove_http.'%');
//        $checkPublisher = Publisher::where('url', 'like', '%'.$url_remove_http.'%');

        $checkExtDomain = ExtDomain::where('domain', $url_remove_http);
        $checkPublisher = Publisher::where('url', $url_remove_http);

        if( $checkExtDomain->count() > 0 || $checkPublisher->count() > 0 ){
            $inputted = '';
            if( $checkExtDomain->count() > 0 ){
                $extdomain = $checkExtDomain->first();
                $inputted = isset($extdomain->users->name) ? $extdomain->users->name : 'Unknown User';
            }

            if( $checkPublisher->count() > 0 ){
                $publisher = $checkPublisher->first();
                $inputted = isset($publisher->user->name) ? $publisher->user->name : 'Unknown User';
            }

            $message = [
                'message' => 'Data is already given. It was inputted by '. strtoupper($inputted),
                'errors' => [
                    'domain' => 'Domain is already exists'
                ]
            ];
            return response()->json($message,422);
        }

        $input['user_id'] = $id;

        // if( $request->status === '100'){
            // Publisher::create([
            //     'user_id' => $id,
            //     'url' => $input['domain'],
            //     'ur' => $input['url_rating'],
            //     'dr' => $input['domain_rating'],
            //     'backlinks' => $input['no_backlinks'],
            //     'ref_domain' => $input['ref_domains'],
            //     'org_keywords' => $input['organic_keywords'],
            //     'org_traffic' => $input['organic_traffic'],
            //     'language_id' => $input['country_id'],
            //     'inc_article' => $input['inc_article'],
            //     'valid' => 'unchecked',
            // ]);

            // $input['user_id'] = $id;
        // }

        if ($this->startsWith($input['domain'], 'https://')) {
            $input['domain'] = explode('https://', $input['domain'])[1];
        }
        else if ($this->startsWith($input['domain'], 'http://')) {
            $input['domain'] = explode('http://', $input['domain'])[1];
        }

        // Validator::make($input, [
        //     'domain' => 'unique:ext_domains'
        // ])->validate();

        $input['status'] = $request->status == "" ? config('constant.EXT_STATUS_NEW'):intval($request->status);
        $hasContactInfo = $this->isInputContactInfo($input);
        $validateRule = [];
//        if ($input['email'] != '') {
//            $validateRule['email'] = ['string', new ExtListEmail()];
//        }

        if ($input['facebook'] != '') {
            $validateRule['facebook'] = ['string', new ExtListLink()];
        }

        if ($input['phone'] != '') {
            $validateRule['phone'] = ['string', new ExtListPhone()];
        }

        Validator::make($input, $validateRule)->validate();

//        if ($input['email'] != '' || $input['email'] > 0) {
//            $input['status'] = config('constant.EXT_STATUS_GOT_EMAIL');
//        } else if ($hasContactInfo && empty($request->status)) {
//            $input['status'] = config('constant.EXT_STATUS_GOT_CONTACTS');
//        }

        if ($this->isInputAfrefInfo($input)) {
            if ($hasContactInfo) {
                $input['status'] = config('constant.EXT_STATUS_AHREAFED');
            } else {
                Validator::make($input, [
                    'facebook' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                ])->validate();
            }
        }

        if ($this->countryRepository->find([['id', $input['country_id']]])->count() == 0) {
            return response()->json(false);
        }

        if (!empty($input['email'])) {

            $input['email'] = implode ('|', $input['email'] );
        } else {
            $input['email'] = null;
        }

        $newExtDomain = $this->extDomainRepository->create($input);
        $newExtDomain->country;

        return response()->json(['success' => true, 'data' => $newExtDomain]);
    }

    public function importExcel(Request $request) {
        $request->validate([
            'file' => 'bail|required|mimes:csv,txt',
            // 'language' => 'required',
            // 'status' => 'required',
        ]);

        $file = $request->all();
        $data = $this->extDomainRepository->importExcel($file);


        if($data['success'] === false){
            unset($data['success']);
            return response()->json($data, 422);
        }

        return response()->json(['success' => true, 'data' => $data['exist'] ], 200);

    }

    private function isInputAfrefInfo($input) {
        if ($input['ahrefs_rank'] > 0 ||
            $input['no_backlinks'] > 0 ||
            $input['url_rating'] > 0 ||
            $input['domain_rating'] > 0 ||
            $input['ref_domains'] > 0 ||
            $input['organic_keywords'] !== '' ||
            $input['organic_traffic'] !== '') {

            return true;
        }

        return false;
    }

    private function isInputContactInfo($input) {
        $email = true;
        if(is_array($input['email'])){
            if(count($input['email']) > 0) {
                $email = true;
            }else{
                $email = false;
            }
        } else{
            $email  = $input['email'] != '' ? true:false;
        }


        if ($input['facebook'] != '' ||
            $input['phone'] != '' ||
            $email ) {
            return true;
        }

        return false;
    }

    public function update(Request $request) {
        $id = Auth::user()->id;

        // $input = $request->only(['info','skype','id', 'status', 'email', 'domain',
        //     'facebook', 'phone', 'ahrefs_rank', 'no_backlinks', 'url_rating', 'domain_rating', 'ref_domains', 'organic_keywords', 'organic_traffic']);

        // check if qualified
        $qualified = ExtDomain::where('id', $request->ext['id'])->first();

        $input['info']  = $request->ext['info'];
        $input['skype']  = $request->ext['skype'];
        $input['id']  = $request->ext['id'];

        // prevent status change if already qualified
        if ($qualified) {
            if ($qualified->status == '100' || $qualified->status == 100) {
                $input['status']  = $qualified->status;
            } else {
                $input['status']  = $request->ext['status'];
            }
        } else {
            $input['status']  = $request->ext['status'];
        }

        $input['email']  = $request->ext['email'];
        $input['domain']  = $request->ext['domain'];
        $input['facebook']  = $request->ext['facebook'];
        $input['phone']  = $request->ext['phone'];
        $input['ahrefs_rank']  = $request->ext['ahrefs_rank'];
        $input['no_backlinks']  = $request->ext['no_backlinks'];
        $input['url_rating']  = $request->ext['url_rating'];
        $input['domain_rating']  = $request->ext['domain_rating'];
        $input['ref_domains']  = $request->ext['ref_domains'];
        $input['organic_keywords']  = $request->ext['organic_keywords'];
        $input['organic_traffic']  = $request->ext['organic_traffic'];
        $input['country_id']  = $request->ext['country_id'];
        $input['user_id'] = $request->ext['user_id'];
        $input['from'] = $request->ext['from'];

        $input['email'] = array_column($input['email'], 'text');


        if ( $request->ext['status'] == 100 && !$request->pub_exists){
            $request->validate([
                'pub.seller' => 'required',
                'pub.language_id' => 'required',
                'pub.inc_article' => 'required',
                'pub.topic' => 'required',
                'pub.casino_sites' => 'required',
                'pub.url' => 'required',
                'pub.price' => 'required',
            ]);
        }

        foreach($input as $key => $value) {
            if (!isset($input[$key])) $input[$key] = '';
        }

        $countryIds = $this->countryRepository->getListCountriesAccess(Auth::id(), true);
        $validateRule = ['id' => ['required', 'integer']];
        $listStatusExclude = [config('constant.EXT_STATUS_CRAWL_FAILED'),
            config('constant.EXT_STATUS_NEW'),
            config('constant.EXT_STATUS_CONTACTED'),
        ];

        $hasContactInfo = $this->isInputContactInfo($input);

        if ($hasContactInfo) {
            array_push($listStatusExclude, config('constant.EXT_STATUS_CONTACTS_NULL'));
        } else {
            array_push($listStatusExclude, config('constant.EXT_STATUS_GOT_CONTACTS'));
            array_push($listStatusExclude, config('constant.EXT_STATUS_REFUSED'));
            array_push($listStatusExclude, config('constant.EXT_STATUS_IN_TOUCHED'));
        }

//        if ($input['email'] != '') {
//            $validateRule['email'] = ['string', new ExtListEmail()];
//        }

        if ($input['facebook'] != '') {
            $validateRule['facebook'] = ['string', new ExtListLink()];
        }

        if ($input['phone'] != '') {
            $validateRule['phone'] = ['string', new ExtListPhone()];
        }

        if ($this->isInputAfrefInfo($input)) {
            array_push($listStatusExclude, config('constant.EXT_STATUS_GOT_CONTACTS'));
            if ($hasContactInfo) {
                array_push($listStatusExclude, config('constant.EXT_STATUS_GOT_CONTACTS'));
            } else {
//                Validator::make($input, [
//                    'facebook' => 'required',
//                    'email' => 'required',
//                    'phone' => 'required',
//                ])->validate();
            }
        } else {
            array_push($listStatusExclude, config('constant.EXT_STATUS_AHREAFED'));
            array_push($listStatusExclude, config('constant.EXT_STATUS_REFUSED'));
            array_push($listStatusExclude, config('constant.EXT_STATUS_IN_TOUCHED'));
        }

        // array_push($listStatusExclude, config('constant.EXT_STATUS_QUALIFIED'));

        $arrayStatusListString = $this->implodeStatusList(",", $listStatusExclude);
        $validateRule['status'] = ['required', 'integer', 'in:'.$arrayStatusListString];
        if (!$this->startsWith($input['domain'], 'https://') && !$this->startsWith($input['domain'], 'http://')) {
            $input['domain'] = 'http://'.$input['domain'];
        }

        // $customMessages = [];

        // foreach ($input['email'] as $key => $value) {
        //     $customMessages['email.' . $key . '.unique'] = $value . ' is already taken.';
        //     $customMessages['email.' . $key . '.email'] = $value . ' is not a valid email.';
        // }

        Validator::make($input, [
            // 'email' => 'array|max:10',
            // 'email.*' => ['email', new EmailPipe('edit', $input['id'])],
            // 'email' => 'email',
            'domain' => 'required|url|max:255',
            'ahrefs_rank' => 'required|integer|gte:0',
            'no_backlinks' => 'required|integer|gte:0',
            'url_rating' => 'required|integer|gte:0',
            'domain_rating' => 'required|integer|gte:0',
            'ref_domains' => 'required|integer|gte:0',
        ])->validate();

        // check if status is 'Qualified'
        if( $input['status'] === '100'){
            $url = remove_http($request->pub['url']);
            $publisher = Publisher::where('url', $url)->count();

            // create a copy if unique URl in list publisher
            if( $publisher == 0 ) {
                Publisher::create([
                    'user_id' => $request->pub['seller'],
                    'url' => $url,
                    'ur' => $input['url_rating'] ?: 0,
                    'dr' => $input['domain_rating'] ?: 0,
                    'backlinks' => $input['no_backlinks'] ?: 0,
                    'ref_domain' => $input['ref_domains'] ?: 0,
                    'org_keywords' => $input['organic_keywords'] ?: 0,
                    'org_traffic' => $input['organic_traffic'] ?: 0,
                    'price' => $request->pub['price'],
                    'language_id' => $request->pub['language_id'],
                    'inc_article' => $request->pub['inc_article'],
                    'topic' => $request->pub['topic'],
                    'casino_sites' => $request->pub['casino_sites'],
                    'valid' => 'valid',
                    'from' => 'prospect',
                ]);

//                $input['user_id'] = $id;
            }
        }

        if ($this->startsWith($input['domain'], 'https://')) {
            $input['domain'] = explode('https://', $input['domain'])[1];
        }
        else if ($this->startsWith($input['domain'], 'http://')) {
            $input['domain'] = explode('http://', $input['domain'])[1];
        }

        // Validator::make($input, [
        //     'domain' => Rule::unique('ext_domains')->ignore($input['id']),
        // ])->validate();

        // Validator::make($input, $validateRule)->validate();

//        if (!isset($input['facebook'])  && !isset($input['email']) && !isset($input['phone'])
//            && $input['status'] === config('constant.EXT_STATUS_GOT_CONTACTS')) {
//            return response()->json(['success' => false, 'message' => 'status invalid']);
//        }

        if (!empty($input['email'])) {

            $input['email'] = implode ('|', $input['email'] );
        } else {
            $input['email'] = null;
        }

        $result = $this->extDomainRepository->updateData($input, $countryIds);
        return response()->json(['success' => $result]);
    }

    private function implodeStatusList($character, $listExclude) {
        $arrayStatusListString = implode(",",  Arr::except($this->validStatus, $listExclude));
        return $arrayStatusListString;
    }

    public function updateStatus(Request $request) {
        $response = ['success' => true];
        $countryIds = $this->countryRepository->getListCountriesAccess(Auth::id(), true);
        $listId = [];

        $validatedData = $request->validate([
            'domain_ids' => 'required',
            'status' => 'required|integer',
        ]);

        $input = $request->all();
        $listId = explode(",", $input['domain_ids']);
        $newStatus = $input['status'];

        if (!$this->isValidExtDomainStatus($newStatus)) {
            $response['success'] = false;
            return response()->json($response);
        }

        $this->extDomainRepository->updateStatus($listId, $newStatus, $countryIds);
        return response()->json($response);
    }

    public function sendEmailJob(Request $request) {
        SendEmailExtJob::dispatch($request)->delay(now()->addSeconds(10));
        return 'Send email success to '.$request->email;
    }

    public function crawlContact(Request $request) {
        $input = $request->all();
        $pushToQueue = true;

        if (!isset($input['domain_ids'])) {
            return response()->json(['success' => false, 'message' => 'id domains is empty']);
        }

        if (isset($input['queue'])) {
            $pushToQueue = $input['queue'];
        }

        $listId = explode(",", $input['domain_ids']);

        // check qualified
        $qualified = ExtDomain::whereIn('id', $listId)->where('status', 100)->count();

        if ($qualified) {
            return response()->json(['success' => false, 'message' => 'Some of the selected items are already qualified. Crawl is not allowed.']);
        }

        $data = $this->extDomainRepository->crawlContact($listId, $pushToQueue);

        if ($pushToQueue === true) {
            return ['success' => true, 'queue' => $pushToQueue];
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function delete(Request $request) {
        $input['deleted_at'] = date('Y-m-d h:i:s');

        if( is_array($request->id) ){
            foreach( $request->id as $ids ){
                $data = json_decode($ids);
                $extDomain = ExtDomain::findOrFail($data->id);
                $extDomain->update($input);
            }
        }

        return response()->json(['success' => true], 200);
    }

    private function isValidExtDomainStatus($checkStatus) {
        foreach($this->validStatus as $status) {
            if ($checkStatus == $status) {
                return true;
            }
        }

        return false;
    }

    public function getAhrefs(Request $request) {
        $input = $request->all();

        if (!isset($input['params']['domain_ids'])) {
            return response()->json(['success' => false, 'message' => 'id domains is empty']);
        }

        $listId = explode(",", $input['params']['domain_ids']);
        $configs = $this->configRepository->getConfigs('ahrefs');
        $data = $this->extDomainRepository->getAhrefs($listId, $configs);
        return response()->json($data);
    }

    public function updateMultipleStatus(Request $request) {
        $request->validate([
            'status' => 'required'
        ]);

        // $test = [];
        if( $request->status == 100){
            $request->validate([
                'seller' => 'required',
                'language_id' => 'required',
                'inc_article' => 'required',
                'topic' => 'required',
                'casino_sites' => 'required',
                'price' => 'required',
            ]);

            foreach( $request->id as $domain ){
                $id = $domain['id'];
                $extDomain = ExtDomain::findOrFail($id);

                if ($extDomain->status != 100) {
                    $extDomain->update(['status' => $request->status]);
                }

                $url = remove_http($domain['domain']);
                $publisher = Publisher::where('url', $url)->count();

                // add url in list publisher if it does not exist
                if( $publisher == 0 ) {
                    Publisher::create([
                        'user_id' => $request->seller,
                        'url' => $url,
                        'ur' => $domain['url_rating'] ?: 0,
                        'dr' => $domain['domain_rating'] ?: 0,
                        'backlinks' => $domain['no_backlinks'] ?: 0,
                        'ref_domain' => $domain['ref_domains'] ?: 0,
                        'org_keywords' => $domain['organic_keywords'] ?: 0,
                        'org_traffic' => $domain['organic_traffic'] ?: 0,
                        'price' => $request->price,
                        'language_id' => $request->language_id,
                        'inc_article' => $request->inc_article,
                        'topic' => $request->topic,
                        'casino_sites' => $request->casino_sites,
                        'valid' => 'valid',
                    ]);
                }
            }

        }else{
            if( is_array($request->id) ) {
                foreach( $request->id as $domain ){
                    $id = $domain['id'];
                    $extDomain = ExtDomain::findOrFail($id);

                    if ($extDomain->status != 100) {
                        $extDomain->update([
                            'status' => $request->status,
                            // 'user_id' => $request->seller,
                        ]);
                    }
                }
            } else {
                $extDomain = ExtDomain::findOrFail($request->id);

                if ($extDomain->status != 100) {
                    $extDomain->update([
                        'status' => $request->status,
                        // 'user_id' => $request->seller,
                    ]);
                }
            }

        }
        // dd($test);

        return response()->json(['success' => true], 200);

    }

    public function getListExtSeller() {
        $ext_seller = User::select('id', 'name', 'username')->where('role_id', 6)->where('isOurs', 1)->get();
        return response()->json($ext_seller);
    }

    public function updateMultipleEmployee(Request $request) {
        $request->validate([
            'ids' => 'required',
            'emp_id' => 'required'
        ]);

        foreach($request->ids as $id) {
            $ext_domain = ExtDomain::find($id);
            $ext_domain->update([
                'user_id' => $request->emp_id
            ]);
        }


        return response()->json(['success' => true],200);
    }

    /**
     * Export all filtered data from db
     * USE THIS IF THE USER WANTS TO EXPORT ALL THE DATA FILTERED AT ONCE
     *
     */
    public function exportFiltered (Request $request) {
        $input = $request->all();

        $items = ExtDomain::select('domain', 'email');

        // Employee Filter
        if (isset($input['employee_id']) && !empty($input['employee_id'])) {
            if (is_array($input['employee_id'])) {
                $items = $items->where(function ($q) use ($input) {
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

        // Email Filter
        if (isset($input['email'])) {
            $items = $items->where('ext_domains.email', 'like', '%' . $input['email'] . '%');
        }

        // Country Filter
        if (isset($input['country_id']) && $input['country_id'] != '0') {
            if (is_array($input['country_id'])) {
                $countryIds = Country::whereIn('name', $input['country_id'])->get()->pluck('id');
                $items = $items->whereIn('country_id', $countryIds);
            } else {
                $countryId = Country::where('name', $input['country_id'])->first()->id;
                $items = $items->where('country_id', $countryId);
            }
        }

        // Email Required filter
        if (isset($input['required_email']) && $input['required_email'] > 0) {
            $items = $items->where('ext_domains.email', '!=', '');
        }

        // Domain Filter
        if (isset($input['domain'])) {
            $items = $items->where('domain', 'like', '%' . $input['domain'] . '%');
        }

        // From Filter
        if (isset($input['from'])) {
//            $items = $items->where('from', $input['from']);

            if (is_array($input['from'])) {
                $items = $items->whereIn('from', $input['from']);
            } else {
                $items = $items->where('from', $input['from']);
            }
        }

        // Status Filter
        if (isset($input['status']) && !empty($input['status']) && $input['status'] != '-1') {
            if (is_array($input['status'])) {
                $items = $items->whereIn('ext_domains.status', $input['status']);
            } else {
                $items = $items->where('ext_domains.status', $input['status']);
            }
        }

        // Alexa Rank Filter
        if (isset($input['alexa_rank_from']) && !empty($input['alexa_rank_from']) && isset($input['alexa_rank_to']) && !empty($input['alexa_rank_to']) ) {
            $items = $items->whereBetween('alexa_rank',[$input['alexa_rank_from'], $input['alexa_rank_to']]);
        }

        // Domain Zone
        if (isset($input['domain_zone']) && !empty($input['domain_zone'])) {
            if (is_array($input['domain_zone'])) {

                $regs = implode(",", $input['domain_zone']);
                $regs = str_replace('.', '', $regs);
                $regs = explode(",", $regs);

                $items = $items->whereIn(DB::raw("REPLACE(REPLACE(SUBSTRING_INDEX(domain, '.', -1),' ',''),'/','')"), $regs);

            } else {

                $regs = str_replace('.', '', $input['domain_zone']);

                $items = $items->whereRaw("REPLACE(REPLACE(SUBSTRING_INDEX(domain, '.', -1),' ',''),'/','') = '$regs'");
            }
        }

        // Date upload filter
        if (!is_array($input['alexa_date_upload'])) {
            $input['alexa_date_upload'] = \GuzzleHttp\json_decode($input['alexa_date_upload'], true);
        }

        if (isset($input['alexa_date_upload']) && $input['alexa_date_upload']['startDate'] != null && $input['alexa_date_upload']['endDate'] != null) {
            $items = $items->whereDate('ext_domains.created_at', '>=', Carbon::create($input['alexa_date_upload']['startDate'])->format('Y-m-d'));
            $items = $items->whereDate('ext_domains.created_at', '<=', Carbon::create($input['alexa_date_upload']['endDate'])->format('Y-m-d'));
        }

        if (isset($input['adv_sort']) && !empty($input['adv_sort'])) {
            foreach ($input['adv_sort'] as &$sort) {
                $sort = \GuzzleHttp\json_decode($sort);
                $items = $items->orderByRaw("$sort->column $sort->sort");
            }
        }

        $output = [];

        $items->chunk(10000, function($results) use (&$output) {
            $output = array_merge($output, $results->map->only(['domain', 'email'])->toArray());
        });

        return $output;
    }

    public function checkUrlsExisting (Request $request) {
        $urls = array_map(function($item) { return remove_http($item['domain']);}, $request->all());

        return Publisher::select('url')
            ->whereIn('url', $urls)
            ->whereHas('user', function ($query) {
                $query->where('status','=','active');
            })
            ->groupBy('url')
            ->get()
            ->pluck('url')
            ->toArray();
    }
}
