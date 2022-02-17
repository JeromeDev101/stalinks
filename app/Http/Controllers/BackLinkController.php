<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\BacklinkRequest;
use App\Http\Requests\UpdateBacklinkRequest;
use App\Models\Backlink;
use App\Repositories\Contracts\BackLinkRepositoryInterface;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Models\Article;
use App\Models\User;
use App\Models\Registration;
use App\Events\ArticleEvent;
use Illuminate\Support\Facades\DB;

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

    public function getBuyerBought()
    {
        return Backlink::select('backlinks.user_id as id', 'users.username', 'users.name')
            ->leftJoin('users', 'users.id', '=', 'backlinks.user_id')
            ->groupBy('backlinks.user_id', 'users.username')
            ->orderBy('users.username', 'asc')
            ->get();
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

    public function statusSummary() {
        $user = \Illuminate\Support\Facades\Auth::user();
        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user->id)->pluck('email');
        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');
        

        $statuses = Backlink::select('status')
                                ->selectRaw('count(*) as total')
                                ->when($user->role_id == 5, function($query) use ($user, $sub_buyer_ids){
                                    $UserId[] = $user->id;

                                    if(count($sub_buyer_ids) > 0) {
                                        return $query->whereIn('user_id', array_merge($sub_buyer_ids->toArray(),$UserId));
                                    } else {
                                        return $query->whereIn('user_id', $UserId);
                                    }
                                })
                                ->groupBy('status')
                                ->get();

        return $statuses;
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
        $seller = DB::table('backlinks')

                    ->join('publisher','backlinks.publisher_id','=','publisher.id')
                    ->join('buyer_purchased','publisher.id','=','buyer_purchased.publisher_id')
                    ->join('users','buyer_purchased.user_id_buyer','=','users.id')
                    ->select('users.id as user_id','users.email as user_primary_email','users.work_mail as user_work_mail')
                    ->where('backlinks.id',$id)
                    ->first();

        $response = ['update_success' => false];
        $input = $request->only('link', 'price', 'anchor_text', 'live_date', 'status', 'user_id', 'url_advertiser', 'title');
        if (isset($request->publisher_id) && $request->publisher_id != null) {
            $input['publisher_id'] = $request->publisher_id;
        }
        $backlink = $this->backLinkRepository->findById($id);

        if( isset($input['status']) && !empty($input['status']) && $input['status'] == 'Live'){
            if( empty($input['title']) && empty($request->link_from) ){
                return response()->json([
                    "message" => 'Incomplete input',
                    "errors" => [
                        "title" => 'Please add title',
                        "link_from" => 'Please add Link From'
                    ],
                ],422);
            }
        }

        if (!$backlink) {
            return response()->json($response);
        }

//        event(new ArticleEvent("Article is now LIVE", $seller->user_id));

        $this->backLinkRepository->update($backlink, $input);
        $response['update_success'] = true;

        return response()->json($response);
    }

    public function deleteBacklinks(Request $request) {
        $backlink = Backlink::find($request->id);

        $article = Article::where('id_backlink', $request->id)->first();

        if( isset($article->id) ){
            $article->delete();
        }

        $backlink->update([
            'deleted_at' => date('Y-m-d')
        ]);

        return response()->json(['success' => true, 'data' => $backlink], 200);
    }

    public function deleteMultipleBacklinks(Request $request) {
        $ids = $request->ids;

        $backlinks = Backlink::whereIn('id', $ids);

        $articles = Article::whereIn('id_backlink', $ids);

        if( isset($articles) ){
            $articles->delete();
        }

        $backlinks->update([
            'deleted_at' => date('Y-m-d')
        ]);

        return response()->json(['success' => true, 'data' => $backlinks], 200);
    }

    public function getStatus()
    {
        $status = $this->backlinkRepository->getStatus();

        return response()->json(['data' => $status], 200);
    }
}
