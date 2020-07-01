<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\BacklinkRequest;
use App\Http\Requests\UpdateBacklinkRequest;
use App\Repositories\Contracts\BackLinkRepositoryInterface;
use App\Repositories\Contracts\CountryRepositoryInterface;

class BackLinkController extends Controller
{
    /**
     * @var BackLinkRepositoryInterface
     */
    private $backLinkRepository;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepository;


    /**
     * BackLinkController constructor.
     *
     * @param BackLinkRepositoryInterface $backLinkRepository
     */
    public function __construct(
        BackLinkRepositoryInterface $backLinkRepository,
        UserService $userService,
        CountryRepositoryInterface $countryRepository
    ) {
        $this->backLinkRepository = $backLinkRepository;
        $this->userService = $userService;
        $this->countryRepository = $countryRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $userId = \Illuminate\Support\Facades\Auth::id();

        if (isset($input['employee_id'])) {
            $userId = $this->userService->checkUserId($input['employee_id']);
        }

        $filters = json_decode($request->params);
        $countryIds = $this->userService->findAccessByCountryByUserId($userId);
        $intDomains = $this->userService->findAccessByIntDomainEmployee($userId);
        $backLinks = $this->backLinkRepository->getBackLink($countryIds, $intDomains, $filters);

        return response()->json($backLinks);
    }

    public function reports(Request $request)
    {
        $input = $request->all();
        if ($input['country_id'] == 0) {
            $input['country_id'] = $input['country_id_of_user'];
        }
        if ($input['status'] === 0) {
            $input['status'] = $input['status_of_backlink'];
        }

        if (!is_array($input['country_id'])) {
            $input['country_id'] = explode(",", $input['country_id']);
        }

        if (!is_array($input['status'])) {
            $input['status'] = explode(",", $input['status']);
        }
        $userId = $this->userService->checkUserId($input['employee_id']);
        $countryIds = $this->countryRepository->findCountryIdList($input['country_id'], $userId);

        if (count($countryIds) === 0) {
            $countryIds = $this->userService->findAccessByCountryByUserId($userId);
        }

        $intDomainIds = $this->userService->findAccessByIntDomainEmployee($userId);

        $total = $this->backLinkRepository->fillterBackLink($countryIds, $intDomainIds, $input['status']);

        return response()->json($total);
    }

    public function reportsPrice(Request $request)
    {
        $input = $request->only('country_id', 'country_id_of_user', 'employee_id');

        if ($input['country_id'] == 0) {
            $input['country_id'] = $input['country_id_of_user'];
        }

        if (!is_array($input['country_id'])) {
            $input['country_id'] = explode(",", $input['country_id']);
        }
        $userId = $this->userService->checkUserId($input['employee_id']);
        $countryIds = $this->countryRepository->findCountryIdList($input['country_id'], $userId);

        if (count($countryIds) === 0) {
            $countryIds = $this->userService->findAccessByCountryByUserId($userId);
        }

        $intDomainIds = $this->userService->findAccessByIntDomainEmployee($userId);

        $total = $this->backLinkRepository->fillterPrice($countryIds, $intDomainIds);

        return response()->json($total);
    }

    public function store(BacklinkRequest $request)
    {
        $input = $request->only('int_domain_id', 'ext_domain_id', 'price');
        $input['status'] = 'Processing';
        $input['user_id'] = Auth::id();
        $backLinks = $this->backLinkRepository->create($input);

        return response()->json(['success' => true, 'data' => $backLinks]);
    }

    public function update(UpdateBacklinkRequest $request, $id)
    {
        $response = ['update_success' => false];
        $input = $request->only('publisher_id', 'link', 'price', 'anchor_text', 'live_date', 'status', 'user_id', 'url_advertiser');
        $backlink = $this->backLinkRepository->findById($id);

        if (!$backlink) {
            return response()->json($response);
        }

        $this->backLinkRepository->update($backlink, $input);
        $response['update_success'] = true;

        return response()->json($response);
    }
}
