<?php

namespace App\Http\Controllers;

use App\Mail\SendResetPasswordEmail;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\AddSubAccountRequest;
use App\Http\Requests\RegistrationAccountRequest;
use App\Http\Requests\UpdateSetPasswordRequest;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailVerification;
use App\Models\BuyerPurchased;
use Illuminate\Support\Facades\App;
use App\Models\Publisher;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use App\Models\Backlink;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountController extends Controller
{

    private $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function store(AccountRequest $request)
    {
        $input = $request->except('company_type');

        if( Auth::user()->role_id == 8 || Auth::user()->role_id == 1 || Auth::user()->role_id == 3 ) {
            $request->validate(['account_validation' => 'required']);
        } else {
            $input['account_validation'] = 'invalid';
        }

        if (isset($input['company_url']) && !empty($input['company_url'])) {
            $input['company_url'] = $this->accountRepository->remove_http($input['company_url']);
        }

        $isTeamSeller = $this->checkTeamSeller();
        unset($input['c_password']);
        if( $isTeamSeller ){
            $input['team_in_charge'] = Auth::user()->id;
        }
        $input['credit_auth'] = 'No';
        $input['password'] = Hash::make($input['password']);
        $input['is_freelance'] = $request->company_type == 'Freelancer' ? 1:0;
        $registration = Registration::create($input);

        $role_id = 6; //default seller
        if( $registration['type'] == 'Seller' ){
            $role_id = 6;
        }

        if( $registration['type'] == 'Buyer' ){
            $role_id = 5;
        }

        if( $registration['type'] == 'Writer' ){
            $role_id = 4;
        }

        // duplicate the record in Users Table
        $data['username'] = $registration['username'];
        $data['name'] = $registration['name'];
        $data['email'] = $registration['email'];
        $data['phone'] = $registration['phone'];
        $data['avatar'] = '/images/noavatar.jpg';
        $data['role_id'] = $role_id;
        $data['type'] = 0;
        $data['isOurs'] = 1;
        $data['skype'] = $registration['skype'] == '' ? 'none':$registration['skype'];
        $data['password'] = $input['password'];
        $data['id_payment_type'] = $registration['id_payment_type'];
        User::create($data);

        return response()->json(['success' => true, 'data' => $registration], 200);
    }

    public function getList(Request $request)
    {
        $user_id = Auth::user()->id;
        $status = $request->status;
        $type = $request->type;
        $search = $request->search;
        $paginate = $request->paginate;
        $team_in_charge = $request->team_in_charge;
        $country = $request->country;
        $commission = $request->commission;
        $credit_auth = $request->credit_auth;
        $isTeamSeller = $this->checkTeamSeller();

        $list = Registration::when( $status, function($query) use ($status){
            return $query->where( 'status', $status );
        })->when( $type, function($query) use ($type){
            return $query->where( 'type', $type );
        })->when( $search, function($query) use ($search){
            return $query->where( 'name', 'LIKE', '%'.$search.'%' )
                ->orWhere( 'email', 'LIKE', '%'.$search.'%' )
                ->orWhere( 'username', 'LIKE', '%'.$search.'%' );
        })->when( $team_in_charge, function($query) use ($team_in_charge){
            return $query->whereHas('team_in_charge', function ($subquery) use( $team_in_charge ) {
                $subquery->where('team_in_charge', $team_in_charge);
            });
        })->when( $country, function($query) use ($country){
            return $query->where( 'country_id', $country );
        })->when( $commission, function($query) use ($commission){
            return $query->where( 'commission', $commission );
        })->when( $credit_auth, function($query) use ($credit_auth){
            return $query->where( 'credit_auth', $credit_auth );
        })->when( $isTeamSeller, function($query) use ($user_id){
            return $query->whereHas('team_in_charge', function ($subquery) use( $user_id ) {
                $subquery->where('team_in_charge', $user_id);
            });
        })
        ->with('team_in_charge:id,name,username')
        ->with('user:id,email')
        ->orderBy('id', 'desc');

        if($paginate === 'All'){
            return response()->json([
                'data' => $list->get(),
                'total' => $list->count(),
            ],200);
        } else {
            return $list->paginate($paginate);
        }

    }

    private function checkTeamSeller() {
        $result = false;
        $user = Auth::user();

        if( $user->role_id == 6 && $user->isOurs == 0 ){
            $result = true;
        }
        return $result;
    }

    public function edit(UpdateAccountRequest $request)
    {
        $inputs = $request->all();
        $this->accountRepository->updateAccount($inputs);
        $response['success'] = true;
        return response()->json($response);
    }

    public function updateRegistrationAccount(Request $request) {
        // dd($request->all());
        $input = $request->except('company_type');
        $request->validate([
            'country_id' => 'required',
            'id_payment_type' => 'required',
            'company_name' => 'required_if:company_type,==,Company',
            'paypal_account' => 'required_if:id_payment_type,==,1',
            'btc_account' => 'required_if:id_payment_type,==,3',
            'skrill_account' => 'required_if:id_payment_type,==,2',
        ]);

        $input['verification_code'] = null;
        $input['is_freelance'] = $request->company_type == 'Freelancer' ? 1:0;

        $registration = Registration::where('email', $request->email)->first();
        $registration->update($input);

        $role_id = 6; //default seller
        if( $registration->type == 'Seller' ){
            $role_id = 6;
        }

        if( $registration->type == 'Buyer' ){
            $role_id = 5;
        }

        if( $registration->type == 'Writer' ){
            $role_id = 4;
        }


        // duplicate the record in Users Table
        $data['username'] = $registration->username;
        $data['name'] = $registration->name;
        $data['email'] = $registration->email;
        $data['phone'] = $registration->phone;
        $data['avatar'] = '/images/noavatar.jpg';
        $data['role_id'] = $role_id;
        $data['type'] = 0;
        $data['isOurs'] = 1;
        $data['password'] = $registration->password;
        $data['id_payment_type'] = $registration->id_payment_type;
        User::create($data);

        return response()->json(['success' => true], 200);
    }

    public function register(RegistrationAccountRequest $request){
        $input = $request->except('c_password');
        $verification_code = md5(uniqid(rand(), true));
        $input['verification_code'] = $verification_code;
        $input['commission'] = 'no';
        $input['credit_auth'] = 'No';
        $input['password'] = Hash::make($input['password']);

        // OLD SENDING OF EMAIL
        // $email = new SendEmailVerification( $request->email, $request->name, $verification_code );
        // $email->sendEmail();

        Registration::create($input);
        return response()->json(['success' => true], 200);
    }

    public function checkVerificationCode(Request $request) {
        $result = false;
        $code = Registration::where('verification_code', $request->code)->first();
        if( isset($code->verification_code) ){
            $result = true;
        }
        return response()->json(['success' => $result]);
    }

    public function setPassword(UpdateSetPasswordRequest $request){
        $input = $request->only('password');

        $registration = Registration::where('verification_code', $request->code)->first();
        $input['verification_code'] = null;
        $input['password'] = Hash::make($input['password']);

        $role_id = 6; //default seller
        if( $registration['type'] == 'Seller' ){
            $role_id = 6;
        }

        if( $registration['type'] == 'Buyer' ){
            $role_id = 5;
        }

        if( $registration['type'] == 'Writer' ){
            $role_id = 4;
        }

        // duplicate the record in Users Table
        $data['username'] = $registration['username'];
        $data['name'] = $registration['name'];
        $data['email'] = $registration['email'];
        $data['phone'] = $registration['phone'];
        $data['avatar'] = '/images/noavatar.jpg';
        $data['role_id'] = $role_id;
        $data['type'] = 0;
        $data['isOurs'] = 1;
        $data['password'] = $input['password'];
        $user = User::create($data);

        $registration->update($input);

        $credentials = [
            'email' => $user->email,
            'password' => $request->password,
        ];

        return response()->json(['success'=> true, 'data' => $user, 'credentials' => $credentials], 200);

    }

    public function getUserRole()
    {
        $userRole = 'Seller';

        $arrUserId = Publisher::select('user_id')->groupBy('user_id')->get()->toArray();

        $getUsers = Registration::select('users.id', 'registration.username')
                                ->where('registration.type',$userRole)
                                ->where('registration.status', 'active')
                                ->whereIn('users.id', $arrUserId)
                                ->orderBy('users.username', 'asc')
                                ->leftJoin('users', 'users.email', '=', 'registration.email')
                                //->pluck('users.username','users.id')
                                ->get();

        return response()->json([
            'data' => $getUsers
        ], 200);
    }

    public function getWalletCredit() {
        $user_id = Auth::user()->id;
        $credit = 0;
        $wallet = 0;
        $deposit = 0;

        // $publisher_ids = BuyerPurchased::select('publisher_id')
        //                                 ->where('user_id_buyer', $user_id)
        //                                 ->where('status', 'Purchased')
        //                                 ->get()
        //                                 ->toArray();

        $registered = Registration::where('email', Auth::user()->email)->first();
        if ( isset($registered->is_sub_account) && $registered->is_sub_account == 1 ) {
            if ( isset($registered->team_in_charge) ) {
                $user_model = User::where('id', $registered->team_in_charge)->first();
                $user_id = isset($user_model->id) ? $user_model->id : Auth::user()->id;
            }
        }

        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user_id)->pluck('email');
        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');

        // dd($sub_buyer_ids);


        $total_paid = Backlink::selectRaw('SUM(price) as total_paid')
                                ->where('status', 'Live')
                                ->where('payment_status', 'Paid')
                                ->where('user_id', $user_id)
                                ->when(count($sub_buyer_ids) > 0, function($query) use ($sub_buyer_ids){
                                    return $query->orWhereIn('user_id', $sub_buyer_ids);
                                })
                                ->get();

        $total_purchased = Backlink::selectRaw('SUM(price) as total_purchased')
                                ->where('user_id', $user_id)
                                ->when(count($sub_buyer_ids) > 0, function($query) use ($sub_buyer_ids){
                                    return $query->orWhereIn('user_id', $sub_buyer_ids);
                                })
                                ->get();

        $total_purchased_paid = Backlink::selectRaw('SUM(price) as total_purchased_paid')
                                ->where('status', 'Live')
                                ->where('user_id', $user_id)
                                ->when(count($sub_buyer_ids) > 0, function($query) use ($sub_buyer_ids){
                                    return $query->orWhereIn('user_id', $sub_buyer_ids);
                                })
                                ->get();

        // $user = User::select('id','name')
        //             ->with('total_wallet')
        //             ->where('id', $user_id)
        //             ->get();

        $wallet_transaction = WalletTransaction::selectRaw('SUM(amount_usd) as amount_usd')
                    ->where('user_id', $user_id)
                    ->where('admin_confirmation', '!=', 'Not Paid')
                    ->get();

        if( isset($wallet_transaction[0]['amount_usd']) ){
            $deposit = round($wallet_transaction[0]['amount_usd']);
        }

        if( isset($wallet_transaction[0]['amount_usd']) ){
            if ( isset($total_purchased[0]['total_purchased']) ) {
                $credit = floatval($wallet_transaction[0]['amount_usd']) - floatval($total_purchased[0]['total_purchased']);
            } else {
                $credit = floatval($wallet_transaction[0]['amount_usd']);
            }
        }

        if( isset($total_paid[0]['total_paid']) && isset($wallet_transaction[0]['amount_usd']) ){
            $wallet = floatval($wallet_transaction[0]['amount_usd']) - floatval($total_paid[0]['total_paid']);
        }

        return [
            'wallet' => $wallet,
            'total_purchased' => floatVal($total_purchased[0]['total_purchased']),
            'total_purchased_paid' => floatVal($total_purchased_paid[0]['total_purchased_paid']),
            'total_paid' => floatVal($total_paid[0]['total_paid']),
            'credit' => round($credit),
            'deposit' => $deposit,
        ];
    }

    public function getTeamInCharge() {
        $team_in_charge = [5,6,7,1];
        $team = User::select('id','name', 'username')->where('isOurs',0)->whereIn('role_id', $team_in_charge)->orderBy('username', 'asc')->get();
        return response()->json(['data'=> $team], 200);
    }

    public function getTeamInChargePerRole(Request $request) {
        $role_id = 0;
        if( $request->role == 'Seller' ){
            $role_id = 6;
        }

        if( $request->role == 'Buyer' ){
            $role_id = 5;
        }

        if( $request->role == 'Writer' ){
            $role_id = 4;
        }

        $team = User::select('id','name', 'username')->where('isOurs',0)->where('role_id', $role_id)->orderBy('username', 'asc')->get();
        return response()->json($team, 200);
    }

    public function getBuyerCommission(Request $request) {
        $registration = Registration::where('email', $request->email)->first();
        return $registration;
    }

    public function storeSubAccount(AddSubAccountRequest $request) {
        $input['name'] = $request->username;
        $input['username'] = $request->username;
        $input['email'] = $request->email;
        $input['password'] = Hash::make($request->password);
        $input['status'] = 'active';
        $input['role_id'] = 5;
        $input['type'] = 0;
        $input['skype'] = 'none';
        $input['phone'] = '+0000000';
        $input['avatar'] = '/images/noavatar.jpg';
        $input['isOurs'] = 1;

        User::create($input);

        unset($input['type']);
        unset($input['role_id']);
        unset($input['avatar']);
        unset($input['isOurs']);
        $input['type'] = 'Buyer';
        $input['team_in_charge'] = Auth::user()->id;
        $input['is_sub_account'] = 1;

        Registration::create($input);


        return response()->json(['success' => true], 200);
    }

    public function getSubAccount(Request $request) {
        $team_in_charge = $request->team_in_charge == '' ?  Auth::user()->id : $request->team_in_charge;
        $registration = Registration::where('team_in_charge', $team_in_charge)->where('is_sub_account', 1)->get();

        return $registration;
    }

    public function deleteSubAccount(Request $request) {
        $registration = Registration::findOrFail($request->id);
        $registration->delete();

        $user = User::where('email', $registration->email)->first();
        $user->delete();

        return response()->json(['success' => true],200);
    }

    public function updateSubAccount(Request $request) {
        // dd($request->all());
        $input = $request->only('username', 'name', 'status');
        if ($request->password != '') {
            $request->validate([
                'c_password' => 'required|same:password'
            ]);

            $input['password'] = Hash::make($request->password);
        }

        $registration = Registration::findOrFail($request->id);
        $registration->update($input);

        $user = User::where('email', $registration->email)->first();
        $user->update($input);

        return response()->json(['success' => true],200);
    }

    public function userEmailFilter() {
        $user_email = User::select('work_mail')->distinct('work_mail')->where('work_mail', '!=', '')->orderBy('work_mail', 'asc')->get();

        return $user_email;
    }

    public function getInfo(Request $request) {
        $registration = Registration::where('verification_code', $request->code)->first();
        return $registration;
    }

    public function forgotPassword(Request $request) {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        $reg = Registration::where('email', $request->email)->first();

        if($reg && !$user) {
            return response()->json(['error'=> 'Your account is unverified, check your email to verify your account or contact an administrator.'],422);
        } else if (!$user) {
            return response()->json(['error'=> 'Your email does not exist, please try again.'],422);
        }

        $user->update([
            'reset_password_token' => Str::random('60')
        ]);

        Mail::to($request->email)->send(new SendResetPasswordEmail($user->reset_password_token, $request->email));

        return response()->json(['success' => true],200);
    }

    public function validateResetPasswordToken(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || $user->reset_password_token !== $request->token) {
            return response()->json(['success' => false], 422);
        }

        return response()->json(['success' => true]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false], 422);
        }

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['success' => true]);
    }

    public function checkVerifiedAccount(Request $request) {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false], 422);
        }

        return response()->json(['success' => true]);
    }

    public function verifyAccount(UpdateAccountRequest $request) {
        $registered = Registration::find($request->id);

        if (!$registered) {
            return response()->json(['success' => false], 422);
        }

        $registered->update([
            'verification_code' => ''
        ]);

        // update registration and user account

        $inputs = $request->all();
        $this->accountRepository->updateAccount($inputs);

        $role_id = 0;
        if( $registered->type == 'Seller' ){
            $role_id = 6;
        }

        if( $registered->type == 'Buyer' ){
            $role_id = 5;
        }

        if( $registered->type == 'Writer' ){
            $role_id = 4;
        }

        $data['name'] = $registered->name;
        $data['username'] = $registered->username;
        $data['email'] = $registered->email;
        $data['password'] = $registered->password;
        $data['type'] = 0;
        $data['avatar'] = '/images/noavatar.jpg';
        $data['isOurs'] = 1;
        $data['skype'] = $registered->skype == '' ? 'none':$registered->skype;
        $data['role_id'] = $role_id;

        User::create($data);

        return response()->json(['success' => true]);

    }
}
