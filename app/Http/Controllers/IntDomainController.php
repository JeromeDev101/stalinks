<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateIntRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Repositories\Contracts\IntDomainRepositoryInterface;
use App\Repositories\Contracts\DomainProviderRepositoryInterface;
use App\Repositories\Contracts\HostingRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class IntDomainController extends Controller
{

    /**
     * @var IntDomainRepositoryInterface
     */
    private $intDomainRepository;

    /**
     * @var DomainProviderRepositoryInterface
     */
    private $domainProviderRepository;

    /**
     * @var HostingRepositoryInterface
     */
    private $hostingRepository;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepository;

    /**
     * IntDomainController constructor.
     *
     * @param IntDomainRepositoryInterface $intDomainRepository
     * @param UserService $userService
     * @param CountryRepositoryInterface $countryRepository
     */
    public function __construct(
        IntDomainRepositoryInterface $intDomainRepository,
        UserService $userService,
        CountryRepositoryInterface $countryRepository,
        DomainProviderRepositoryInterface $domainProviderRepository,
        HostingRepositoryInterface $hostingRepository
    ) {
        $this->intDomainRepository = $intDomainRepository;
        $this->userService = $userService;
        $this->countryRepository = $countryRepository;
        $this->hostingRepository = $hostingRepository;
        $this->domainProviderRepository = $domainProviderRepository;

    }

    public function reports(Request $request)
    {
        $input = $request->all();

        $userId = $this->userService->checkUserId($input['employee_id']);
        if (isset($input['country_id']) && $input['country_id'] > 0) {
            $countryIds = explode(",", $input['country_id']);
            $countryIds = $this->countryRepository->validCountryIdList($countryIds, $userId);
        } else {
            $countryIds = $this->countryRepository->getListCountriesAccess($userId);
        }

        $intDomainIds = $this->userService->findAccessByIntDomainEmployee($userId);
        $total = $this->intDomainRepository->fillterIntDomain($countryIds, $intDomainIds);

        return response()->json($total);
    }

    public function index()
    {
        $intdomains = $this->intDomainRepository->getIntDomain();

        return response()->json($intdomains);
    }

    public function getList(Request $request) {
        $input = $request->all();
        //init filter
        $countryIds = [];
        $countryOfUserIds = [];
        $countryExceptIds = [];
        $isFullList = false;
        $allInt = false;
        $page = 0;
        $perPage =  10;
        $userId = Auth::id();
        $format = false;
        $onlyInt = false;
        $getAll = false;

        if (isset($input['employee_id'])) {
            $userId = $this->userService->checkUserId($input['employee_id']);
        }

        if (isset($input['format']) && $input['format'] == 1) {
            $format = true;
        }

        if (isset($input['only_int']) && $input['only_int'] == 1) {
            $onlyInt = true;
        }

        if (isset($input['get_all']) && $input['get_all'] == 1) {
            $getAll = true;
        }

        if (isset($input['full_page'])) {
            $isFullList = $input['full_page'];
        }

        $filters = [
            'whereIn' => [],
            'where' => [],
            'orWhereIn' => []
        ];

        if (isset($input['page'])) {
            $page = $input['page'];
        }

        if (isset($input['per_page'])) {
            $perPage = $input['per_page'];
        }

        if (isset($input['country_id']) && $input['country_id'] > 0) {
            $countryIds = explode(",", $input['country_id']);
            if ($getAll === false) {
                $countryOfUserIds = $this->countryRepository->validCountryIdListOnlyUser($countryIds, $userId);
            } else {
                if (Auth::user()->isAdmin()) {
                    $countryOfUserIds = $this->countryRepository->validCountryIdListAllList($countryIds, $userId);
                } else {
                    $countryOfUserIds = $this->countryRepository->validCountryIdList($countryIds, $userId);
                }
            }

            $countryExceptIds = array_diff($countryIds, $countryOfUserIds);
            $allInt = false;
        } else {
            $countryOfUserIds = $this->countryRepository->getListCountriesAccess($userId);
            $allInt = true;
        }

        $intDomainIds = $this->userService->findAccessByIntDomainEmployee($userId);
        if ($onlyInt === false) $filters['whereIn'][] = ['country_id', $countryOfUserIds];
        if (isset($input['domain']) && $input['domain'] != '') {
            $filters['where'][] = ['domain', 'like', '%'.$input['domain'].'%'];
        }

        if ($format) {
            $data = $this->intDomainRepository->paginateFormat($page, $perPage, $filters, $isFullList, $countryOfUserIds, $countryExceptIds, $intDomainIds, $allInt);
        } else {
            $data = $this->intDomainRepository->paginate($page, $perPage, $filters, $isFullList, $countryOfUserIds, $countryExceptIds, $intDomainIds, $allInt);
        }

        return response()->json($this->addPaginationRaw($data));
    }

    public function getIntByHosting($id)
    {
        $countryIds = $this->userService->findAccessByCountry();
        $intByHosting = $this->intDomainRepository->findIntByHosting($countryIds, $id);

        return response()->json($intByHosting);
    }

    public function store(Request $request) {
        $input = $request->only(['domain', 'country_id', 'domain_provider_id', 'hosting_provider_id']);

        Validator::make($input, [
            'domain' => 'required|url|max:255',
            'country_id' => 'required|integer|not_in:0',
            'domain_provider_id' => 'required|integer|not_in:0',
            'hosting_provider_id' => 'required|integer|not_in:0',
        ])->validate();

        Validator::make($input, [
            'domain' => 'required|unique:int_domains',
        ])->validate();

        $input['user_id'] = Auth::id();

        if ($this->countryRepository->find([['id', $input['country_id']]])->count() == 0) {
            return response()->json(['country_id' => 'does not exist'], 422);
        }

        if ($this->hostingRepository->find([['id', $input['hosting_provider_id']]])->count() == 0) {
            return response()->json(['hosting_provider_id' => 'does not exist'], 422);
        }

        if ($this->domainProviderRepository->find([['id', $input['domain_provider_id']]])->count() == 0) {
            return response()->json(['domain_provider_id' => 'does not exist'], 422);
        }

        $newIntDomain = $this->intDomainRepository->create($input);
        return response()->json(['success' => true, 'data' => $newIntDomain]);
    }

    public function update(UpdateIntRequest $request) {
        $response = ['success' => false];
        $input = $request->only(['id', 'domain', 'country_id', 'domain_provider_id', 'hosting_provider_id']);

        $intDomain = $this->intDomainRepository->findById($input['id']);
        if (!$intDomain) {
            return response()->json($response);
        }

        $this->intDomainRepository->update($intDomain, $input);
        $response['success'] = true;
        return response()->json($response);
    }

    public function getIntByDomain($id)
    {
        $countryIds = $this->userService->findAccessByCountry();
        $intByHosting = $this->intDomainRepository->findIntByDomain($countryIds, $id);

        return response()->json($intByHosting);
    }
}
