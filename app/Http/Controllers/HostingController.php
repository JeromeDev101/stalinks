<?php

namespace App\Http\Controllers;

use App\Http\Requests\HostingRequest;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Repositories\Contracts\HostingRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class HostingController extends Controller
{
    /**
     * @var HostingRepositoryInterface
     */
    private $hostingRepository;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        HostingRepositoryInterface $hostingRepository,
        UserService $userService
    ) {
        $this->hostingRepository = $hostingRepository;
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

        $intDomainIds = $this->userService->findAccessByIntDomainEmployee(Auth::id());
        $countryIds = $this->userService->findAccessByCountry();
        $hostings = $this->hostingRepository->getHosting($countryIds, $intDomainIds, $isFullDataAcessable, $querySearch);

        return response()->json($hostings);
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
    public function store(HostingRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();
        $hostingCreate = $this->hostingRepository->create($input);

        return response()->json(['success' => true, 'data' => $hostingCreate]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $intDomainIds = $this->userService->findAccessByIntDomainEmployee(Auth::id());
        $countryIds = $this->userService->findAccessByCountry();
        $detailHosting = $this->hostingRepository->showDetail($id, $countryIds, $intDomainIds);

        return response()->json($detailHosting);
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
    public function update(HostingRequest $request, $id)
    {
        $response = ['success' => false];
        $input = $request->all();
        $intDomainIds = $this->userService->findAccessByIntDomainEmployee(Auth::id());
        $countryIds = $this->userService->findAccessByCountry();
        $hosting = $this->hostingRepository->showDetail($id, $countryIds, $intDomainIds);
        if (!$hosting) {
            return response()->json($response);
        }

        $this->hostingRepository->update($hosting, $input);
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
