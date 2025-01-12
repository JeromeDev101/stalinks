<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCountryRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Repositories\Contracts\CountryRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Country;
use App\Models\Language;

class CountryController extends Controller
{
    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepository;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * CountryController constructor.
     *
     * @param CountryRepositoryInterface $countryRepository
     * @param UserService $userService
     */
    public function __construct(CountryRepositoryInterface $countryRepository, UserService $userService)
    {
        $this->countryRepository = $countryRepository;
        $this->userService = $userService;
    }

    public function getCountries(Request $request) {
        $input = $request->all();
        $forExt = false;
        $forAssign = false;

        // this code will allow the non-employee to have a dropdown of Country [temporary] (3/7/20) - jerome
        $user = User::find($request->user_id);
        if( isset( $user->isOurs ) ){
            if( $user->isOurs == 1 ){
                $input['paginate'] = 1;
                $input['page'] = 1;
                $input['per_page'] = 50;
            }
        }
            
        // ================

        if (isset($input['for_ext'])) {
            $forExt = $input['for_ext'];
        }

        if (isset($input['for_assign'])) {
            $forAssign = $input['for_assign'];
        }

        if (isset($input['paginate']) && $input['paginate'] == 1) {
            $filters = [
                'whereIn' => [],
                'orWhereIn' => [],
                'where' => []
            ];

            if (isset($input['page'])) {
                $page = $input['page'];
            }

            if (isset($input['per_page'])) {
                $perPage = $input['per_page'];
            }

            if (isset($input['name']) && $input['name'] != '') {
                $filters['where'][] = ['name', 'like', '%'.$input['name'].'%'];
            }

            if (isset($input['code']) && $input['code'] != '') {
                $filters['where'][] = ['code', '=', $input['code']];
            }

            return $this->countryRepository->paginate($page, $perPage, $filters);

        } else {
            if (isset($input['user_id']) && $input['user_id'] > 0) {
                if ($forAssign === false) {
                    $extDomainIds = $this->userService->findExtDomainIdsFromInt($input['user_id']);
                } else {
                    $extDomainIds = [];
                }

                $data = $this->countryRepository->getListCountriesModelAccess($input['user_id'], $extDomainIds, $forExt);
            } else {
                $data = $this->countryRepository->findAll();
            }

            return response()->json([
                'data' => $data,
                'total' => count($data)
            ]);
        }
    }

    public function store(Request $request) {
        $input = $request->only(['name', 'code']);

        Validator::make($input, [
            'name' => 'required|unique:countries|max:255',
            'code' => 'required|unique:countries|max:255',
        ])->validate();

        $newCountry = $this->countryRepository->create($input);
        return response()->json(['success' => true, 'data' => $newCountry]);
    }

    public function edit(UpdateCountryRequest $request) {
        $response = ['success' => false];
        $input = $request->all();

        $country = $this->countryRepository->findById($input['id']);
        if (!$country) {
            return response()->json($response);
        }

        $this->countryRepository->update($country, $input);
        $response['success'] = true;
        return response()->json($response);
    }

    public function getListCountry() {
        $country = Country::orderBy('name', 'asc');
        return response()->json([
            'data' => $country->get(),
            'count' => $country->count(),
        ],200);
    }

    public function getListLanguages() {
        $language = Language::orderBy('name', 'asc');
        return response()->json([
            'data' => $language->get(),
            'count' => $language->count(),
        ],200);
    }
}
