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
        return response()->json($newData);
    }

    private function getAlexaInstance($countryCode, $start, $count) {
        $alexa = new Alexa($countryCode, $start, $count);
        $configs = $this->configRepository->getConfigs('alexa');
        $alexa->setConfigs($configs);

        return $alexa;
    }

    public function getList(Request $request) {
        $input = $request->except('status');

        $page = 0;
        $perPage =  10;
        $userId = Auth::id();
        $findAllExt = true;
        $countriesExceptIds = [];
        $requireEmail = false;
        $sort = [
            'id', 'desc'
        ];

        if (isset($input['employee_id'])) {
            $userId = $this->userService->checkUserId($input['employee_id']);
        }

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
            $countryAll = explode(",", $input['country_id']);
            $countryIds = $this->countryRepository->validCountryIdList($countryAll, $userId, true);
            $findAllExt = false;
            $countriesExceptIds = array_diff($countryAll, $countryIds);
        } else {
            $countryIds = $this->countryRepository->getListCountriesAccess($userId, true);
        }

        $countryIdsInt = $this->countryRepository->getListCountriesAccess($userId);

        $filters['whereIn'][] = ['country_id', $countryIds];

        $filters['other']['whereIn'][] = ['country_id', $countryIds];
        $filters['other']['orWhereIn'][] = ['country_id', $countriesExceptIds];

        if( isset($input['status_multiple']) ){
            $filters['whereIn'][] = ['status', $input['status_multiple']];
        }

        // if (isset($input['status']) && $input['status'] >= 0) {
        //     $filters['whereIn'][] = ['status', explode(",", $input['status'])];
        // }

        if (isset($input['required_email']) && $input['required_email'] > 0) {
            $filters['where'][] = ['email', '!=', ''];
        }

        if (isset($input['email'])) {
            $filters['where'][] = ['email', 'like', '%'.$input['email'].'%'];
        }

        if (isset($input['domain']) && $input['domain'] != '') {
            $filters['where'][] = ['domain', 'like', '%'.$input['domain'].'%'];
        }
        
        $extDomainIds = $this->userService->findExtDomainIdsFromInt($userId);
        $data = $this->extDomainRepository->paginate($page, $perPage, $filters,  $countryIds, $countryIdsInt, $countriesExceptIds, $findAllExt, $sort, $extDomainIds);
        return response()->json($this->addPaginationRaw($data));
    }

    public function store(Request $request) {
        $input = $request->only(['domain', 'country_id', 'alexa_rank',
            'ahrefs_rank', 'no_backlinks', 'url_rating', 'domain_rating', 'ref_domains', 'organic_keywords', 'organic_traffic', 'email',
            'facebook', 'phone']);

        foreach($input as $key => $value) {
            if (!isset($input[$key])) $input[$key] = '';
        }

        $newStatus = 0;

        Validator::make($input, [
            'domain' => 'required|url|max:255',
            'country_id' => 'required|integer|not_in:0',
            'alexa_rank' => 'required|integer|gte:0',
            'ahrefs_rank' => 'required|integer|gte:0',
            'no_backlinks' => 'required|integer|gte:0',
            'url_rating' => 'required|integer|gte:0',
            'domain_rating' => 'required|integer|gte:0',
            'ref_domains' => 'required|integer|gte:0'
        ])->validate();

        if ($this->startsWith($input['domain'], 'https://')) {
            $input['domain'] = explode('https://', $input['domain'])[1];
        }
        else if ($this->startsWith($input['domain'], 'http://')) {
            $input['domain'] = explode('http://', $input['domain'])[1];
        }

        Validator::make($input, [
            'domain' => 'unique:ext_domains'
        ])->validate();

        $input['status'] = config('constant.EXT_STATUS_NEW');
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
        if ($hasContactInfo) {
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
        $id = Auth::user()->id;

        $input = $request->only(['id', 'status', 'email', 'domain',
            'facebook', 'phone', 'ahrefs_rank', 'no_backlinks', 'url_rating', 'domain_rating', 'ref_domains', 'organic_keywords', 'organic_traffic']);

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
                'user_id' => $id,
                'url' => $input['domain'],
                'ur' => $input['url_rating'],
                'dr' => $input['domain_rating'],
                'backlinks' => $input['no_backlinks'],
                'ref_domain' => $input['ref_domains'],
                'org_keywords' => $input['organic_keywords'],
                'org_traffic' => $input['organic_traffic'],
                'price' => null,
            ]);
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
}
