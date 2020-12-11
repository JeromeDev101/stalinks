<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailExtJob;
use App\Libs\Alexa;
use App\Repositories\Contracts\ConfigRepositoryInterface;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Repositories\Contracts\CrawlContactRepositoryInterface;
use App\Rules\ExtListEmail;
use App\Rules\ExtListLink;
use App\Rules\ExtListPhone;
use Illuminate\Support\Arr;
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
use League\OAuth2\Server\RequestEvent;

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
       // $input = $request->except('status');

        $input = $request->except('employee_id', 'country_id');
        $page = 0;
        $perPage =  50;
        $userId = Auth::id();
        $findAllExt = true;
        $countriesExceptIds = [];
        $requireEmail = false;
        $sort = [
            'id', 'desc'
        ];
        
        $emp_ids = [];
        if( is_array($request->employee_id) && count($request->employee_id) > 0 ){
            foreach( $request->employee_id as $name){
                $user = User::where('username', 'like', '%'.$name.'%')->first();
                $emp_ids[] = $user->id;
            }
        }    

        $cntry_ids = [];
        if( is_array($request->country_id) && count($request->country_id) > 0 ){
            foreach( $request->country_id as $name){
                $cntry = Country::where('name', 'like', '%'.$name.'%')->first();
                $cntry_ids[] = $cntry->id;
            }
        } 
        if (count($cntry_ids) > 0){
            $input['country_id'] = $cntry_ids;
        }else{
            $input['country_id'] = null;
        }

        // dd($input);

        // if (isset($input['employee_id'])) {
        //     $userId = $this->userService->checkUserId($input['employee_id']);
        // }

        if (isset($input['sort'])) {
            $sortTemp = explode(",", $input['sort']);
            $sort = [];
            $sort[] = 'ext_domains.'.$sortTemp[0];
            $sort[1] = $sortTemp[1];
        }

        $filters = [
            'whereIn' => [],
            'where' => [],
            'other' => [
                'whereIn' => [],
                'where' => [],
            ]
        ];

        if (isset($input['page'])) {
            $page = $input['page'];
        }

        if (isset($input['per_page'])) {
            $perPage = $input['per_page'];
        }

        //init filter
        $countryIds = [];
        if (isset($input['country_id']) && $input['country_id'] > 0) {
            $countryAll = $input['country_id'];
            $countryIds = $this->countryRepository->validCountryIdList($countryAll, $userId, true);
            $findAllExt = false;
            $countriesExceptIds = array_diff($countryAll, $countryIds);
        } else {
            $countryIds = $this->countryRepository->getListCountriesAccess($userId, true);
        }

        // to display the list of extdomain to non employee (jerome) - 7/3/20
        $non_employee = $this->giveAccessToNonEmployees($userId);
        if( $non_employee ){
            $countryIds = $non_employee;
        }

        $countryIdsInt = $this->countryRepository->getListCountriesAccess($userId);


        $filters['whereIn'][] = ['country_id', $countryIds];
        
        if (count($emp_ids) > 0){
            $filters['whereIn'][] = ['user_id', $emp_ids];
        }
        

        $filters['other']['whereIn'][] = ['country_id', $countryIds];
        $filters['other']['orWhereIn'][] = ['country_id', $countriesExceptIds];

        if( isset($input['status_multiple']) ){
            $filters['whereIn'][] = ['status', $input['status_multiple']];
        }

        if (isset($input['country_id']) && $input['country_id'] != '') {
            $filters['whereIn'][] = ['country_id', $input['country_id']];
        }

        if (isset($input['required_email']) && $input['required_email'] > 0) {
            $filters['where'][] = ['email', '!=', ''];
        }

        if (isset($input['email'])) {
            $filters['where'][] = ['email', 'like', '%'.$input['email'].'%'];
        }


        if (isset($input['domain']) && $input['domain'] != '') {
            $filters['where'][] = ['domain', 'like', '%'.$input['domain'].'%'];
        }

        if (isset($input['status']) && $input['status'] > 0 ) {
            $filters['whereIn'][] = ['status', explode(",", $input['status'])];
        }

        $extDomainIds = $this->userService->findExtDomainIdsFromInt($userId);
        $data = $this->extDomainRepository->paginate($page, $perPage, $filters,  $countryIds, $countryIdsInt, $countriesExceptIds, $findAllExt, $sort, $extDomainIds);
        return response()->json($this->addPaginationRaw($data));
    }

    private function giveAccessToNonEmployees($userId) {
        $user = User::find($userId);
        $result = false;
        if( isset( $user->isOurs ) ){
            if( $user->isOurs == 1 ){
                $result = Country::pluck('id')->toArray();
            }
        }
        return $result;
    }

    public function store(Request $request) {
        $id = Auth::user()->id;
        $input = $request->only(['info','skype','domain', 'country_id', 'alexa_rank',
            'ahrefs_rank', 'no_backlinks', 'url_rating', 'domain_rating', 'ref_domains', 'organic_keywords', 'organic_traffic', 'email',
            'facebook', 'phone']);

        foreach($input as $key => $value) {
            if (!isset($input[$key])) $input[$key] = '';
        }

        $newStatus = 0;

        Validator::make($input, [
            'domain' => 'required|max:255',
            'country_id' => 'required|integer|not_in:0',
            'alexa_rank' => 'required|integer|gte:0',
            'ahrefs_rank' => 'required|integer|gte:0',
            'no_backlinks' => 'required|integer|gte:0',
            'url_rating' => 'required|integer|gte:0',
            'domain_rating' => 'required|integer|gte:0',
            'ref_domains' => 'required|integer|gte:0'
        ])->validate();

        $checkExtDomain = ExtDomain::where('domain', 'like', '%'.$input['domain'].'%');
        $checkPublisher = Publisher::where('url', 'like', '%'.$input['domain'].'%');
        
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
                'message' => 'It was inputted by '. $inputted,
                'errors' => [
                    'domain' => 'Domain is already exists'
                ]
            ];
            return response()->json($message,422);
        }

        if( $request->status === '100'){
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

            $input['user_id'] = $id;
        }

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
        if ($input['email'] != '') {
            $validateRule['email'] = ['string', new ExtListEmail()];
        }

        if ($input['facebook'] != '') {
            $validateRule['facebook'] = ['string', new ExtListLink()];
        }

        if ($input['phone'] != '') {
            $validateRule['phone'] = ['string', new ExtListPhone()];
        }

        Validator::make($input, $validateRule)->validate();
        if ($hasContactInfo && empty($request->status)) {
            $input['status'] = config('constant.EXT_STATUS_GOT_CONTACTS');
        }

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
        if ($input['facebook'] != '' ||
            $input['phone'] != '' ||
            $input['email'] != '' ) {
            return true;
        }

        return false;
    }

    public function update(Request $request) {
        // dd($request->all());
        $id = Auth::user()->id;

        // $input = $request->only(['info','skype','id', 'status', 'email', 'domain',
        //     'facebook', 'phone', 'ahrefs_rank', 'no_backlinks', 'url_rating', 'domain_rating', 'ref_domains', 'organic_keywords', 'organic_traffic']);

        $input['info']  = $request->ext['info'];
        $input['skype']  = $request->ext['skype'];
        $input['id']  = $request->ext['id'];
        $input['status']  = $request->ext['status'];
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

        if ( $request->ext['status'] == 100){
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

        // dd($input);

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

        if ($input['email'] != '') {
            $validateRule['email'] = ['string', new ExtListEmail()];
        }

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
                Validator::make($input, [
                    'facebook' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                ])->validate();
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

        Validator::make($input, [
            'domain' => 'required|url|max:255',
            'ahrefs_rank' => 'required|integer|gte:0',
            'no_backlinks' => 'required|integer|gte:0',
            'url_rating' => 'required|integer|gte:0',
            'domain_rating' => 'required|integer|gte:0',
            'ref_domains' => 'required|integer|gte:0',
        ])->validate();

        if( $input['status'] === '100'){
            Publisher::create([
                'user_id' => $request->pub['seller'],
                'url' => $request->pub['url'],
                'ur' => $input['url_rating'],
                'dr' => $input['domain_rating'],
                'backlinks' => $input['no_backlinks'],
                'ref_domain' => $input['ref_domains'],
                'org_keywords' => $input['organic_keywords'],
                'org_traffic' => $input['organic_traffic'],
                'price' => $request->pub['price'],
                'language_id' => $request->pub['language_id'],
                'inc_article' => $request->pub['inc_article'],
                'topic' => $request->pub['topic'],
                'casino_sites' => $request->pub['casino_sites'],
                'valid' => 'unchecked',
            ]);

            $input['user_id'] = $id;
        }

        if ($this->startsWith($input['domain'], 'https://')) {
            $input['domain'] = explode('https://', $input['domain'])[1];
        }
        else if ($this->startsWith($input['domain'], 'http://')) {
            $input['domain'] = explode('http://', $input['domain'])[1];
        }

        Validator::make($input, [
            'domain' => Rule::unique('ext_domains')->ignore($input['id']),
        ])->validate();

        Validator::make($input, $validateRule)->validate();

//        if (!isset($input['facebook'])  && !isset($input['email']) && !isset($input['phone'])
//            && $input['status'] === config('constant.EXT_STATUS_GOT_CONTACTS')) {
//            return response()->json(['success' => false, 'message' => 'status invalid']);
//        }

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
        $data = $this->extDomainRepository->crawlContact($listId, $pushToQueue);

        if ($pushToQueue === true) {
            return ['success' => true, 'queue' => $pushToQueue];
        }

        return $data;
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

        if (!isset($input['domain_ids'])) {
            return response()->json(['success' => false, 'message' => 'id domains is empty']);
        }

        $listId = explode(",", $input['domain_ids']);
        $configs = $this->configRepository->getConfigs('ahrefs');
        $data = $this->extDomainRepository->getAhrefs($listId, $configs);
        return response()->json($data);
    }

    public function updateMultipleStatus(Request $request) {
        $request->validate([
            'status' => 'required'
        ]);

        // $test = [];
        if( $request->status == 100 ){
            $request->validate([
                'seller' => 'required',
            ]);

            foreach( $request->id as $domain ){
                $id = $domain['id'];
                $extDomain = ExtDomain::findOrFail($id);
                $extDomain->update(['status' => $request->status]);

                Publisher::create([
                    'user_id' => $request->seller,
                    'url' => $domain['domain'],
                    'ur' => $domain['url_rating'],
                    'dr' => $domain['domain_rating'],
                    'backlinks' => $domain['no_backlinks'],
                    'ref_domain' => $domain['ref_domains'],
                    'org_keywords' => $domain['organic_keywords'],
                    'org_traffic' => $domain['organic_traffic'],
                    'price' => $domain['price'],
                    'language_id' => $domain['country']['id'],
                    'inc_article' => $domain['inc_article'],
                    'valid' => 'unchecked',
                ]);
            }

        }else{
            if( is_array($request->id) ) {
                foreach( $request->id as $domain ){
                    $id = $domain['id'];
                    $extDomain = ExtDomain::findOrFail($id);
                    $extDomain->update([
                        'status' => $request->status,
                        'user_id' => $request->seller,
                    ]);
                }
            } else {
                $extDomain = ExtDomain::findOrFail($request->id);
                $extDomain->update([
                    'status' => $request->status,
                    'user_id' => $request->seller,
                ]);
            }
                
        }
        // dd($test);

        return response()->json(['success' => true], 200);
   
    }

    public function getListExtSeller() {
        $ext_seller = User::select('id', 'name', 'username')->where('role_id', 6)->where('isOurs', 1)->get();
        return response()->json($ext_seller);
    }
}
