<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\DomainProviderRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Repositories\Contracts\DomainProviderRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DomainProviderController extends Controller
{
    /**
     * @var DomainProviderRepositoryInterface
     */
    private $domainProviderRepository;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        DomainProviderRepositoryInterface $domainProviderRepository,
        UserService $userService
    ) {
        $this->domainProviderRepository = $domainProviderRepository;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $isFullDataAcessable = false;
        if (isset($request->full_data)) {
            $isFullDataAcessable = $request->full_data;
        }
        
        $querySearch = '';
        if (isset($request->params)) {
            $querySearch = $request->params;
        }

        $countryIds = $this->userService->findAccessByCountry();
        $intDomainIds = $this->userService->findAccessByIntDomainEmployee(Auth::id());
        $domains = $this->domainProviderRepository->getDomainProvider($countryIds, $intDomainIds, $isFullDataAcessable, $querySearch);

        return response()->json($domains);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DomainProviderRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();
        $domainCreate = $this->domainProviderRepository->create($input);

        return response()->json(['success' => true, 'data' => $domainCreate]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $countryIds = $this->userService->findAccessByCountry();
        $intDomainIds = $this->userService->findAccessByIntDomainEmployee(Auth::id());
        $detailDomain = $this->domainProviderRepository->showDetail($id, $intDomainIds, $countryIds);

        return response()->json($detailDomain);
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
    public function update(UpdateDomainRequest $request, $id)
    {
        $response = ['success' => false];
        $input = $request->all();

        $countryIds = $this->userService->findAccessByCountry();
        $intDomainIds = $this->userService->findAccessByIntDomainEmployee(Auth::id());
        $domain = $this->domainProviderRepository->showDetail($id, $intDomainIds, $countryIds);

        if (!$domain) {
            return response()->json($response);
        }

        $this->domainProviderRepository->update($domain, $input);
        $response['success'] = true;
        
        return response()->json($response);
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
}
