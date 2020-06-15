<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\Log;
use App\Models\PaymentType;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Repositories\Contracts\IntDomainRepositoryInterface;
use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepositoryInterface;
use Faker\Provider\ar_SA\Payment;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepository;

    /**
     * @var IntDomainRepositoryInterface
     */
    private $intDomainRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param IntDomainRepositoryInterface $intDomainRepository
     * @param CountryRepositoryInterface $countryRepository
     */
    public function __construct(UserRepositoryInterface $userRepository,
                                IntDomainRepositoryInterface $intDomainRepository,
                                CountryRepositoryInterface $countryRepository)
    {
        $this->userRepository = $userRepository;
        $this->intDomainRepository = $intDomainRepository;
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
        $perPage = config('common.paginate.default');
        $filters = [
            'whereIn' => [],
            'where' => []
        ];

        if (isset($input['email']) && $input['email'] != '') {
            $filters['where'][] = ['email', 'like', '%'.$input['email'].'%'];
        }

        if (isset($input['work_mail']) && $input['work_mail'] != '') {
            $filters['where'][] = ['work_mail', 'like', '%'.$input['work_mail'].'%'];
        }

        if (isset($input['phone']) && $input['phone'] != '') {
            $filters['where'][] = ['phone', 'like', '%'.$input['phone'].'%'];
        }

        if (isset($input['name']) && $input['name'] != '') {
            $filters['where'][] = ['name', 'like', '%'.$input['name'].'%'];
        }

        $isFullList = false;
        if (isset($request->full_data)) {
            $isFullList = $request->full_data;
        }

        if (isset($input['per_page'])) {
            $perPage = $input['per_page'];
        }

        $users = $this->userRepository->getUser($isFullList, $perPage, $filters);

        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userInfo = $this->userRepository->showInfo($id);

        return response()->json($userInfo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function currentInforUser()
    {
        $userInfo = $this->userRepository->showInfo(Auth::id());

        return response()->json($userInfo);
    }

    public function checkUserAdmin() {
        $isAdmin = Auth::user()->isAdmin();
        return response()->json($isAdmin);
    }

    public function getTypes() {
        $data = [
            0 => 'Employee',
            1 => 'Others',
            10 => 'Admin'
        ];

        return response()->json($data);
    }

    public function editPermissions(Request $request) {
        $input= $request->all();
        $forExt = false;
        if (!isset($input['id'])) {
            return response()->json(['id' => 'empty user id'], 422);
        }

        if (!isset($input['countries_id'])) {
            return response()->json(['countries_id' => 'empty countries id'], 422);
        }

        $user = $this->userRepository->findById($input['id']);
        if (!$user) {
            return response()->json(['id' => 'user does not existed'], 422);
        }

        if (isset($input['for_ext']) && $input['for_ext'] == 1) {
            $forExt = true;
        }

        if ($forExt === false) {
            $user->countriesAccessable()->sync($input['countries_id']);
            $this->addLog('App\Models\UserCountry', config('constant.ACTION_UPDATE'), ['user' => $user->id, 'sync' => $input['countries_id']]);
        } else {
            $user->countriesExtAccessable()->sync($input['countries_id']);
            $this->addLog('App\Models\UserCountryExt', config('constant.ACTION_UPDATE'), ['user' => $user->id, 'sync' => $input['countries_id']]);
        }
        return response()->json(['success' => true]);
    }

    public function editIntPermissions(Request $request) {
        $input= $request->all();

        if (!isset($input['id'])) {
            return response()->json(['id' => 'empty user id'], 422);
        }

        if (!isset($input['int_domains_id'])) {
            return response()->json(['int_domains_id' => 'empty int_domains id'], 422);
        }

        $user = $this->userRepository->findById($input['id']);
        if (!$user) {
            return response()->json(['id' => 'user does not existed'], 422);
        }

        $user->internalDomainsAccessable()->sync($input['int_domains_id']);
        $this->addLog('App\Models\UserIntDomain', config('constant.ACTION_UPDATE'), ['user' => $user->id, 'sync' => $input['int_domains_id']]);
        return response()->json(['success' => true]);
    }

    public function getCountriesIntDomain() {
        $countriesIds = [];
        $userId = Auth::id();
        $mapCountryId = [];
        $user = $this->userRepository->findById($userId);
        foreach ($user->internalDomainsAccessable as $int) {
            $countriesIds[] = $int->country_id;
            $mapCountryId[$int->country_id] = true;
        }

        $countries = $this->countryRepository->findByIds($countriesIds)->toArray();
        foreach($user->countriesAccessable as $item) {
            if (isset($mapCountryId[$item->id]) && $mapCountryId[$item->id] === true) continue;
            $countries[] = $item;
        }

        return response()->json([
            'data' => $countries,
            'total' => count($countries)
        ]);
    }

    protected function addLog(string $table, string $action, $payload = []) {
        $payload['is_admin'] = Auth::user()->isAdmin();

        Log::create([
            'table' => $table,
            'action' => $action,
            'user_id' => Auth::id(),
            'payload' => json_encode($payload)
        ]);
    }

    public function getPaymentList()
    {
        $list = PaymentType::all();

        return response()->json([
            'data' => $list
        ]);
    }

}
