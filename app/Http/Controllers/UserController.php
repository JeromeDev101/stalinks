<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\MailDraft;
use App\Models\Config;
use App\Models\Log;
use App\Models\PaymentType;
use App\Models\Reply;
use App\Models\User;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Repositories\Contracts\IntDomainRepositoryInterface;
use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepositoryInterface;
use Faker\Provider\ar_SA\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if (isset($input['type']) && $input['type'] != '') {
            $filters['where'][] = ['type', $input['type']];
        }

        if (isset($input['status']) && $input['status'] != '') {
            $filters['where'][] = ['status', $input['status']];
        }

        if (isset($input['role']) && $input['role'] != '') {
            $filters['where'][] = ['role_id', $input['role']];
        }

        if (isset($input['work_mail']) && $input['work_mail'] != '') {
            $filters['where'][] = ['work_mail', 'like', '%'.$input['work_mail'].'%'];
        }

        if (isset($input['phone']) && $input['phone'] != '') {
            $filters['where'][] = ['phone', 'like', '%'.$input['phone'].'%'];
        }

        if (isset($input['skype']) && $input['skype'] != '') {
            $filters['where'][] = ['skype', 'like', '%'.$input['skype'].'%'];
        }

        if (isset($input['name']) && $input['name'] != '') {
            $filters['where'][] = ['username', 'like', '%'.$input['name'].'%'];
        }

        //it doesnt show the seller/buyer
        $filters['where'][] = ['isOurs',0];

        $isFullList = false;
        if ($request->per_page === 'All') {
            $isFullList = true;
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

        $list[5]->account = Config::where('code', 'payoneer')->first()->value;
        $list[4]->address = Config::where('code', 'usdt_address')->first()->value;
        $list[3]->address = Config::where('code', 'eth_address')->first()->value;
        $list[2]->address = Config::where('code', 'btc_address')->first()->value;
        $list[1]->email = Config::where('code', 'skrill')->first()->value;

        return response()->json([
            'data' => $list
        ]);
    }

    public function uploadAvatar(Request $request) {
        $filename = 'photo_avatar_' . Auth::id() . '.' . $request->photo->getClientOriginalExtension();
        $extension = $request->photo->getClientOriginalExtension();
        move_file_to_storage($request->photo, 'images/avatar', $filename);

        $user = User::find(Auth::id());
        if($user) {
            $user->update([
                'avatar' => '/images/avatar/'.$filename
            ]);
        }

        return response()->json(['result' => true, 'extension'=> $extension], 200);
    }

    public function getUnreadEmails($email)
    {
        $unread_emails = User::selectRaw('(select count(distinct CONCAT("Re: ", REPLACE(subject, "Re: ", "")), CONCAT(LEAST(sender, received), "-", GREATEST(sender, received))) as aggregate from replies where is_viewed = 0 and is_sent = 0 and deleted_at is null and replies.received like CONCAT("%", users.work_mail ,"%")) as unread_count')
            ->where('work_mail', $email)
            ->get();

        return response()->json([
            'count' => $unread_emails[0]->unread_count,
        ]);
    }

    public function getUnreadEmailList($email, $all)
    {
        $inbox = Reply::selectRaw('
                    MAX(replies.sender) AS sender,
                    MAX(replies.received) AS received,
                    MAX(replies.id) AS id,
                    MIN(replies.subject) as subject,
                    CONCAT("Re: ", REPLACE(subject, "Re: ", "")) AS subject2,
                    CONCAT(LEAST(sender, received), "-", GREATEST(sender, received)) as concat_result,
                    MIN(CONCAT("Re: ", subject)) AS con_sub,
                    MIN(REPLACE(subject, "Re: ", "")) AS re_sub,
                    MAX(labels.name) AS label_name,
                    MAX(replies.is_sent) AS is_sent,
                    MAX(labels.color) AS label_color,
                    MAX(replies.label_id) AS label_id,
                    MAX(replies.from_mail) AS from_mail,
                    MAX(replies.created_at) AS created_at,
                    MAX(replies.attachment) AS attachment,
                    MAX(replies.stored_attachments) AS stored_attachments,
                    MAX(replies.is_starred) AS is_starred,
                    MAX(replies.status_code) AS status
                ')
            ->leftJoin('labels', 'replies.label_id', '=', 'labels.id')
            ->where('replies.is_sent', 0)
            ->where('replies.is_viewed', 0)
            ->whereNull('replies.deleted_at')
            ->where('replies.received', 'like', '%' . $email . '%')
            ->groupBy(DB::raw('CONCAT("Re: ", REPLACE(subject, "Re: ", "")), sender, received'))
            ->orderBy('id', 'desc');

            if ($all == 'true') {
                $inbox = $inbox->paginate(10);
            } else {
                $inbox = $inbox->take(10)->get();
            }

        return $inbox;
    }

    public function getUserDrafts()
    {
        $draft_count = MailDraft::where('user_id', Auth::id())->count();

        return response()->json([
            'count' => $draft_count
        ]);
    }

    // subscription
    public function subscribeUser(Request $request)
    {
        $user = User::where('subscription_code', $request->code)->first();

        if ($user) {

            if ($request->mode === 'yes') {
                $user->is_subscribed = 1;
            } else {
                $user->is_subscribed = 0;
            }

            $user->save();
        }
    }

    public function hasUserSubscribed(Request $request)
    {
        return User::where('subscription_code', $request->code)->where('is_subscribed', 1)->first();
    }

    public function checkSubscriptionCode(Request $request)
    {
        $code = User::where('subscription_code', $request->code)->first();

        return response()->json($code);
    }
}
