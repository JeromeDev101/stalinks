<?php

namespace App\Http\Controllers;

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

class AccountController extends Controller
{

    public function store(AccountRequest $request)
    {
        $input = $request->all();
        $isTeamSeller = $this->checkTeamSeller();
        unset($input['c_password']);
        if( $isTeamSeller ){
            $input['team_in_charge'] = Auth::user()->id;
        }
        $input['password'] = Hash::make($input['password']);
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
        $isTeamSeller = $this->checkTeamSeller();

        $list = Registration::when( $status, function($query) use ($status){
            return $query->where( 'status', $status );
        })->when( $type, function($query) use ($type){
            return $query->where( 'type', $type );
        })->when( $search, function($query) use ($search){
            return $query->where( 'name', 'LIKE', '%'.$search.'%' )
                ->orWhere( 'email', 'LIKE', '%'.$search.'%' )
                ->orWhere( 'username', 'LIKE', '%'.$search.'%' );
        })
        ->when( $team_in_charge, function($query) use ($team_in_charge){
            return $query->whereHas('team_in_charge', function ($subquery) use( $team_in_charge ) {
                $subquery->where('team_in_charge', $team_in_charge);
            });
        })
        ->when( $isTeamSeller, function($query) use ($user_id){
            return $query->whereHas('team_in_charge', function ($subquery) use( $user_id ) {
                $subquery->where('team_in_charge', $user_id);
            });
        })
        ->with('team_in_charge:id,name,username')
        ->orderBy('id', 'desc')
        ->paginate($paginate);

        return $list;

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
        $response['success'] = false;
        $input = $request->all();
        unset($input['c_password']);

        if (isset($input['password']) && $input['password'] != '') {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $account = Registration::find($input['id']);
        if (!$account) {
            return response()->json($response);
        }

        // ---------------------------------------------------

        $user = User::where('email', $account->email);

        if (isset($input['password']) && $input['password'] != '') {
            $user->update(['password' => $input['password']]);
        }

        if (isset($input['credit_auth']) && $input['credit_auth'] != '') {
            $user->update(['credit_auth' => $input['credit_auth']]);
        }

        if (isset($input['email']) && $input['email'] != '') {
            $user->update(['email' => $input['email']]);
        }

        if (isset($input['username']) && $input['username'] != '') {
            $user->update(['username' => $input['username']]);
        }

        if (isset($input['status']) && $input['status'] != '') {
            $user->update(['status' => $input['status']]);
        }

        if (isset($input['name']) && $input['name'] != '') {
            $user->update(['name' => $input['name']]);
        }

        if (isset($input['id_payment_type']) && $input['id_payment_type'] != '') {
            $user->update(['id_payment_type' => $input['id_payment_type']]);
        }

        // ---------------------------------------------------

        $account->update($input);

        $user = User::where('email', $input['email'])->first();

        $dataUser = [
            'username' => $request->username
        ];

        if(!is_null($user)) {
            $user->update($dataUser);
        }

        $response['success'] = true;
        return response()->json($response);
    }

    public function register(RegistrationAccountRequest $request){
        $input = $request->all();
        $verification_code = md5(uniqid(rand(), true));
        $input['verification_code'] = $verification_code;
        $input['commission'] = 'no';

        $email = new SendEmailVerification( $request->email, $request->name, $verification_code );
        $email->sendEmail();

        Registration::create($input);

        return response()->json(['success' => true], 200);
    }

    public function checkVerificationCode(Request $request) {
        $result = false;
        $code = Registration::where('verification_code', $request->code)->first();
        if( $code['verification_code'] ){
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


        $total_paid = Backlink::selectRaw('SUM(price) as total_paid')
                                ->where('status', 'Live')
                                ->where('payment_status', 'Paid')
                                ->where('user_id', $user_id)
                                ->get();

        $total_purchased = Backlink::selectRaw('SUM(price) as total_purchased')
                                ->where('user_id', $user_id)
                                ->get();

        $total_purchased_paid = Backlink::selectRaw('SUM(price) as total_purchased_paid')
                                ->where('status', 'Live')
                                ->where('user_id', $user_id)
                                ->get();

        $user = User::select('id','name')
                    ->with('total_wallet')
                    ->where('id', $user_id)
                    ->get();

        $wallet_transaction = WalletTransaction::selectRaw('SUM(amount_usd) as amount_usd')
                    ->where('user_id', $user_id)
                    ->get();
        
        if( isset($wallet_transaction[0]['amount_usd']) ){
            $deposit = number_format($wallet_transaction[0]['amount_usd'],2);
        }

        if( isset($wallet_transaction[0]['amount_usd']) && isset($total_purchased[0]['total_purchased']) ){
            $credit = floatval($wallet_transaction[0]['amount_usd']) - floatval($total_purchased[0]['total_purchased']);
        }

        if( isset($total_paid[0]['total_paid']) && isset($wallet_transaction[0]['amount_usd']) ){
            $wallet = floatval($wallet_transaction[0]['amount_usd']) - floatval($total_paid[0]['total_paid']);
        }
    
        return [
            'wallet' => $wallet,
            'total_purchased' => floatVal($total_purchased[0]['total_purchased']),
            'total_purchased_paid' => floatVal($total_purchased_paid[0]['total_purchased_paid']),
            'total_paid' => floatVal($total_paid[0]['total_paid']),
            'credit' => number_format($credit,2),
            'deposit' => $deposit,
        ];
    }

    public function getTeamInCharge() {
        $team_in_charge = [5,6,7,1];
        $team = User::select('id','name', 'username')->where('isOurs',0)->whereIn('role_id', $team_in_charge)->orderBy('username', 'asc')->get();
        return response()->json(['data'=> $team], 200);
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
        $registration = Registration::where('team_in_charge', Auth::user()->id)->where('is_sub_account', 1)->get();

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
}
