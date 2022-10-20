<?php

namespace App\Http\Controllers;

use App\Events\TeamInChargeUpdatedEvent;
use App\Mail\SendResetPasswordEmail;
use App\Models\Language;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Rules\PaymentInfoExists;
use Carbon\Carbon;
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
use App\Models\PaymentType;
use App\Models\UsersPaymentType;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{

    private $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function store(AccountRequest $request)
    {
        $input = $request->except(
            'company_type',
            'add_method_payment_type',
            'bank_name',
            'account_name',
            'account_iban',
            'swift_code',
            'beneficiary_add',
            'account_holder',
            'account_type',
            'routing_num',
            'wire_routing_num'
        );

        if( Auth::user()->role_id == 8 || Auth::user()->role_id == 1 || Auth::user()->role_id == 3 ) {
            $request->validate(['account_validation' => 'required']);
        } else {
            $input['account_validation'] = 'processing';
        }

        if (isset($input['company_url']) && !empty($input['company_url'])) {
            $input['company_url'] = remove_http($input['company_url']);
        }

        $isTeamSeller = $this->checkTeamSeller();

        unset($input['c_password']);
        if( $isTeamSeller ){
            $input['team_in_charge'] = Auth::user()->id;
        }

        // if writer
        if($input['type'] == 'Writer') {
            if($input['rate_type'] == 'ppw') {
                $input['writer_price'] = '0.0085';
            } else {
                $input['writer_price'] = '10';
            }

            $input['language_id'] = json_encode($input['language_id']);
        }

        $input['commission'] = 'yes'; // default
        $input['credit_auth'] = 'No';
        $input['affiliate_id'] = isset($input['affiliate']) ? $input['affiliate']:null;
        $input['password'] = Hash::make($input['password']);
        $input['is_freelance'] = $request->company_type == 'Freelancer' ? 1:0;

        // DB transaction
        DB::beginTransaction();

            // insert in registration
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

            if( $registration['type'] == 'Affiliate' ){
                $role_id = 11;
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
            $user = User::create($data);

            // Insert users payments types
            $insert_input_users_payment_type = [];
            if(is_array($request->add_method_payment_type)) {

                // validate payment info
                if (isset($request->add_method_payment_type) && $request->add_method_payment_type && $user) {
                    $request->validate([
//                        'add_method_payment_type.*' => 'unique:users_payment_type,account,' . $user->id . ',user_id'
                        'add_method_payment_type.*' => [new PaymentInfoExists($user->id)],
                    ], [
                        'add_method_payment_type.*.unique' => 'Payment info :input is already taken by another user.',
                    ]);
                }

                foreach($request->add_method_payment_type as $key => $types) {
                    if($types != '') {
                        array_push($insert_input_users_payment_type, [
                            'user_id' => $user['id'],
                            'payment_id' => $key,
                            'account' => $types,
                            'bank_name' => count($request->bank_name) > 0 ? json_encode($request->bank_name):null,
                            'account_name' => count($request->account_name) > 0 ? json_encode($request->account_name):null,
                            'account_iban' => count($request->account_iban) > 0 ? json_encode($request->account_iban):null,
                            'swift_code' => count($request->swift_code) > 0 ? json_encode($request->swift_code):null,
                            'beneficiary_add' => count($request->beneficiary_add) > 0 ? json_encode($request->beneficiary_add):null,
                            'account_holder' => count($request->account_holder) > 0 ? json_encode($request->account_holder):null,
                            'account_type' => count($request->account_type) > 0 ? json_encode($request->account_type):null,
                            'routing_num' => count($request->routing_num) > 0 ? json_encode($request->routing_num):null,
                            'wire_routing_num' => count($request->wire_routing_num) > 0 ? json_encode($request->wire_routing_num):null,
                            'is_default' => $key == $request->id_payment_type ? 1:0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }
            }

            if (count($insert_input_users_payment_type)) {
                 UsersPaymentType::insert($insert_input_users_payment_type);
            }

            // insert language writer price rate
            if (isset($request->language_id)) {
                if ($request->type === 'Writer') {
                    $languages = Language::whereIn('id', $request->language_id)
                        ->get();

                    $languages_temp = $languages->mapWithKeys(function ($language) use ($request){
                        return [$language->id => [
                            'type' => $request->rate_type,
                            'rate' => $request->rate_type === 'ppa' ? $language->ppa_rate : $language->ppw_rate
                        ]];
                    });

                    $user->languages()->sync($languages_temp->all());
                }
            }

        if($user && $registration) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return response()->json(['success' => true], 200);
    }

    public function getList(Request $request)
    {
        $user_id = Auth::user()->id;
        $status = $request->status;
        $type = $request->type;
        $search = $request->search;
        $paginate = $request->paginate;
        $team_in_charge = $request->team_in_charge;
        $affiliate = $request->affiliate;
        $country = $request->country;
        $language_id = $request->language_id;
        $commission = $request->commission;
        $credit_auth = $request->credit_auth;
        $company_type = ($request->company_type == '' || $request->company_type == null)
            ? null : ($request->company_type == 1 ? 'Freelance' : 'Company');
        $company_name = $request->company_name;
        $company_url = $request->company_url;
        $account_validation = $request->account_validation;
        $payment_info = $request->payment_info;
        $payment_info_data = $request->payment_info_data;
        $is_sub_account = ($request->is_sub_account == '' || $request->is_sub_account == null)
            ? null : ($request->is_sub_account == 1 ? 'Sub' : 'Not');
        $created_at = json_decode($request->created_at);
        $isTeamSeller = $this->checkTeamSeller();
        $buyer_transaction = $request->buyer_transaction;

        // advance search

        $advance_search = $request->advance_search;

        $list = Registration::when( $status, function($query) use ($status){
            return $query->where( 'status', $status );
        })->when( $type, function($query) use ($type){
            return $query->where( 'type', $type );
        })->when( $search, function($query) use ($search){
//            return $query->where( 'name', 'LIKE', '%'.$search.'%' )
//                ->orWhere( 'email', 'LIKE', '%'.$search.'%' )
//                ->orWhere( 'username', 'LIKE', '%'.$search.'%' );

            return $query->where(function ($queryPar) use ($search) {
                $queryPar->where( 'name', 'LIKE', '%'.$search.'%' )
                    ->orWhere( 'email', 'LIKE', '%'.$search.'%' )
                    ->orWhere( 'username', 'LIKE', '%'.$search.'%' );
            });
        })->when( $team_in_charge, function($query) use ($team_in_charge){
            if($team_in_charge == 'none') {
                return $query->where('team_in_charge', null);
            } else {
                return $query->whereHas('team_in_charge', function ($subquery) use( $team_in_charge ) {
                    $subquery->where('team_in_charge', $team_in_charge);
                });
            }
        })->when( $affiliate, function($query) use ($affiliate){
            return $query->whereHas('affiliate', function ($subquery) use( $affiliate ) {
                $subquery->where('affiliate_id', $affiliate);
            });
        })->when( $country, function($query) use ($country){
            if($country == 'none') {
                return $query->where('country_id', null);
            } else {
                return $query->where( 'country_id', $country );
            }
        })->when( $language_id, function($query) use ($language_id){
            if($language_id == 'none') {
                return $query->where('language_id', null);
            } else {
                return $query->where( 'language_id', $language_id );
            }
        })->when( $commission, function($query) use ($commission){
            return $query->where( 'commission', $commission );
        })->when( $credit_auth, function($query) use ($credit_auth){
            return $query->where( 'credit_auth', $credit_auth );
        })->when( $company_type, function($query) use ($company_type){
            $comp_type = $company_type == 'Freelance' ? 1 : 0;
            return $query->where( 'is_freelance', $comp_type);
        })->when( $company_name, function($query) use ($company_name){
            return $query->where( 'company_name', 'like', '%' . $company_name . '%' );
        })->when( $company_url, function($query) use ($company_url){
            return $query->where( 'company_url', 'like', '%' . $company_url . '%' );
        })->when( $account_validation, function($query) use ($account_validation){
            return $query->where( 'account_validation', $account_validation);
        })->when( $is_sub_account, function($query) use ($is_sub_account){
            $is_sub = $is_sub_account == 'Sub' ? 1 : 0;
            return $query->where( 'is_sub_account', $is_sub);
        })->when( $created_at->startDate, function($query) use ($created_at){
            return $query->whereDate('created_at', '>=', Carbon::create($created_at->startDate)->format('Y-m-d'))
                ->whereDate('created_at', '<=', Carbon::create($created_at->endDate)->format('Y-m-d'));
        })->when( $isTeamSeller, function($query) use ($user_id){
//            return $query->whereHas('team_in_charge', function ($subquery) use( $user_id ) {
//                $subquery->where('team_in_charge', $user_id);
//            });

            return $query->where(function ($query2) use ($user_id) {
                $query2->whereHas('team_in_charge', function ($sub) use( $user_id ) {
                    $sub->where('registration.team_in_charge', $user_id)
                    ->orWhere(function($query) use ($user_id) {
                        $query->whereIn('type', ['Seller', 'Writer'])
                            ->where('users.status', 'inactive');
                    });
                })->orWhere(function($query) {
                    $query->whereNull('team_in_charge')
                        ->where('type', 'Seller')
                        ->orWhere('type', 'Writer');
                });
            });
        })
        ->when($request->account_verification, function ($query) use ($request) {
            if ($request->account_verification == 'Yes') {
                $query->whereNull('verification_code');
            } else {
                $query->whereNotNull('verification_code');
            }

            return $query;
        })
        ->when(isset($payment_info), function($query) use ($payment_info){
            $query->whereHas('user', function($_query) use ($payment_info){
                $_query->whereHas('userPaymentTypes', function($sub) use ($payment_info) {
                    $sub->where('payment_id', $payment_info)->where('is_default', 1);
                });
            });
        })
        ->when(isset($payment_info_data), function($query) use ($payment_info_data){
            $query->whereHas('user', function($_query) use ($payment_info_data){
                $_query->whereHas('userPaymentTypes', function($sub) use ($payment_info_data) {
                    $sub->where( 'account', 'LIKE', '%'.$payment_info_data.'%' )->where('is_default', 1);
                });
            });
        })
        ->when(isset($buyer_transaction), function($query) use ($buyer_transaction){
            if ($buyer_transaction === 'with') {
                $query->where('type', 'Buyer')
                    ->whereNull('verification_code')
                    ->has('user.wallet_transactions');
            } else {
                $query->where('type', 'Buyer')
                    ->whereNull('verification_code')
                    ->where('account_validation', 'valid')
                    ->doesntHave('user.wallet_transactions');
            }
        })

        // advance search

        ->when(isset($advance_search), function($query) use ($advance_search){
            $query->whereHas('user', function($sub) use ($advance_search){
                $sub->where('id', 'LIKE', '%' . $advance_search . '%');
            })
            ->orWhere('created_at', 'LIKE', '%' . $advance_search . '%')
            ->orWhereHas('user', function($sub) use ($advance_search){
                $sub->whereHas('userPaymentTypes', function($sub_2) use ($advance_search) {
                    $sub_2->where( 'account', 'LIKE', '%' . $advance_search . '%')->where('is_default', 1);
                });
            })
            ->orWhere( 'email', 'LIKE', '%' . $advance_search . '%' )
            ->orWhereHas('team_in_charge', function ($sub) use( $advance_search ) {
                $sub->where('username', 'LIKE', '%' . $advance_search . '%');
            })
           ->orWhereHas('affiliate', function ($sub) use( $advance_search ) {
               $sub->where('username', 'LIKE', '%' . $advance_search . '%');
            })
            ->orWhere( 'name', 'LIKE', '%'.$advance_search.'%' )
            ->orWhere( 'username', 'LIKE', '%'.$advance_search.'%' )
            ->orWhereHas('country', function ($sub) use( $advance_search ) {
                $sub->where('name', 'LIKE', '%' . $advance_search . '%');
            })
            ->orWhereHas('language', function ($sub) use( $advance_search ) {
                $sub->where('name', 'LIKE', '%' . $advance_search . '%');
            })
            ->orWhere( 'company_name', 'LIKE', '%'.$advance_search.'%' )
            ->orWhere( 'company_url', 'LIKE', '%'.$advance_search.'%' );
        })

        ->with([
            'team_in_charge:id,name,username,status',
            'team_in_charge' => function ($query) {
                $query->with(['registration' => function ($query2) {
                    $query2->with('team_in_charge');
                }]);
            }
        ])
        ->with(['user' => function($query) {
            $query->with(['userPaymentTypes' => function($sub) {
                $sub->with('paymentType');
            }])
            ->with('languages')
            ->withCount('wallet_transactions');
        }])
        ->with('affiliate:id,name,username')
        ->with('country:id,name')
        ->with('language:id,name')
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
        $user = Auth::user();
        return ($user->role_id == 6 && $user->isOurs == 0);
    }

    public function edit(UpdateAccountRequest $request)
    {
        $inputs = $request->all();

        if(isset($request->language_id)) {
            $inputs['language_id'] = json_encode($request->language_id);
        }

        if($inputs['status'] == 'inactive') {
            $inputs['account_validation'] = 'invalid';
        } else {
            $request->validate([
//                'company_name' => ['required_if:company_type,==,Company'],
                'company_name' => [Rule::requiredIf($inputs['company_type'] == 'Company' && $inputs['isVerified'])],
            ]);
        }

        if( $request->account_validation != 'invalid' ) {
            $request->validate([
//                'writer_price' => 'required_if:type,==,Writer',
                'language_id' => 'required_if:type,==,Writer',
                'rate_type' => 'required_if:type,==,Writer',
                'writer_language_price_rates.*.rate' => 'required_if:type,==,Writer',
            ], [
                'writer_language_price_rates.*.required_if' =>  'The language price rate must not be empty if account type is Writer.',
            ]);
        }

        $user = User::where('email', $request->email)->first();

        // validate payment info
        if (isset($request->update_method_payment_type) && $request->update_method_payment_type && $user) {
            $request->validate([
//                'update_method_payment_type.*' => 'unique:users_payment_type,account,' . $user->id . ',user_id'
                'update_method_payment_type.*' => [new PaymentInfoExists($user->id)],
            ], [
                'update_method_payment_type.*.unique' => 'Payment info :input is already taken by another user.',
            ]);
        }

        $data = $this->accountRepository->updateAccount($inputs);
        $response['success'] = true;
        return response()->json($response);
    }

    public function getPaymentList() {
        $list = PaymentType::all();

        return response()->json([
            'data' => $list
        ]);
    }

    /**
     *
     * transfer payment information from registration to users_payment_type table
     */
    public function transferPaymentInfo() {
        $users = User::select('id', 'email')
                            ->with('registration:id,email,id_payment_type,paypal_account,btc_account,skrill_account')
                            ->get();
        $insert_array = [];
        foreach($users as $user) {
            // check if there's a registration data
            if(isset($user->registration)) {

                // check if there's default payment type
                if($user->registration->id_payment_type != '') {

                    // 1 - id
                    if($user->registration->paypal_account != '') {
                        array_push($insert_array,[
                            'user_id' => $user->id,
                            'payment_id' => 1,
                            'account' => $user->registration->paypal_account,
                            'is_default' => $user->registration->id_payment_type == 1 ? 1:0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }

                    // 3 - id
                    if($user->registration->btc_account != '') {
                        array_push($insert_array,[
                            'user_id' => $user->id,
                            'payment_id' => 3,
                            'account' => $user->registration->btc_account,
                            'is_default' => $user->registration->id_payment_type == 3 ? 1:0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }

                    // 2 - id
                    if($user->registration->skrill_account != '') {
                        array_push($insert_array,[
                            'user_id' => $user->id,
                            'payment_id' => 2,
                            'account' => $user->registration->skrill_account,
                            'is_default' => $user->registration->id_payment_type == 2 ? 1:0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }

            }
        }

        UsersPaymentType::insert($insert_array);

        return response()->json([
            'success' => true
        ], 200);
    }


    public function updateRegistrationAccount(Request $request) {
        $input = $request->except(
            'company_type',
            'payment_type',
            'bank_name',
            'account_name',
            'account_iban',
            'swift_code',
            'beneficiary_add',
            'account_holder',
            'account_type',
            'routing_num',
            'wire_routing_num'
        );

        $request->validate([
            'country_id' => 'required',
            'language_id' => 'required_if:type,==,Writer',
            'rate_type' => 'required_if:type,==,Writer',
            'company_name' => 'required_if:company_type,==,Company',
            // 'paypal_account' => 'required_if:id_payment_type,==,1',
            // 'btc_account' => 'required_if:id_payment_type,==,3',
            // 'skrill_account' => 'required_if:id_payment_type,==,2',
        ]);

        $input['verification_code'] = null;
        $input['is_freelance'] = $request->company_type == 'Freelancer' ? 1:0;

        // validation of sellers and writer ofr payment info
        if($input['type'] === 'Writer' || $input['type'] === 'Seller') {
            $request->validate([
                'payment_type' => 'required',
                'id_payment_type' => 'required',
            ]);
        }

        $registration = Registration::where('email', $request->email)->first();

        if ($input['type'] === 'Affiliate') {
            $input['account_validation'] = 'valid';
        }

        //default pricing for writer
        if ($input['type'] === 'Writer') {
            if($input['rate_type'] == 'ppw') {
                $input['writer_price'] = '0.0085';
            } else {
                $input['writer_price'] = '10';
            }

            $input['language_id'] = json_encode($input['language_id']);
        }

        // DB transaction
        DB::beginTransaction();

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

            if( $registration->type == 'Affiliate' ){
                $role_id = 11;
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
            $user = User::create($data);

            // validate payment info
            if (isset($request->payment_type) && $request->payment_type && $user) {
                $request->validate([
                    'payment_type.*' => 'unique:users_payment_type,account,' . $user->id . ',user_id'
                ], [
                    'payment_type.*.unique' => 'Payment info :input is already taken by another user.',
                ]);
            }

            // Insert users payments types
            $insert_input_users_payment_type = [];
            if(is_array($request->payment_type)) {
                foreach($request->payment_type as $key => $types) {
                    if($types != '') {
                        array_push($insert_input_users_payment_type, [
                            'user_id' => $user['id'],
                            'payment_id' => $key,
                            'account' => $types,
                            'bank_name' => count($request->bank_name) > 0 ? json_encode($request->bank_name):null,
                            'account_name' => count($request->account_name) > 0 ? json_encode($request->account_name):null,
                            'account_iban' => count($request->account_iban) > 0 ? json_encode($request->account_iban):null,
                            'swift_code' => count($request->swift_code) > 0 ? json_encode($request->swift_code):null,
                            'beneficiary_add' => count($request->beneficiary_add) > 0 ? json_encode($request->beneficiary_add):null,
                            'account_holder' => count($request->account_holder) > 0 ? json_encode($request->account_holder):null,
                            'account_type' => count($request->account_type) > 0 ? json_encode($request->account_type):null,
                            'routing_num' => count($request->routing_num) > 0 ? json_encode($request->routing_num):null,
                            'wire_routing_num' => count($request->wire_routing_num) > 0 ? json_encode($request->wire_routing_num):null,
                            'is_default' => $key == $request->id_payment_type ? 1:0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }
            }

            if (count($insert_input_users_payment_type)) {
                UsersPaymentType::insert($insert_input_users_payment_type);
            }

            // insert language writer price rate
            if (isset($request->language_id)) {
                if ($request->type === 'Writer') {
                    $languages = Language::whereIn('id', $request->language_id)
                        ->get();

                    $languages_temp = $languages->mapWithKeys(function ($language) use ($request){
                        return [$language->id => [
                            'type' => $request->rate_type,
                            'rate' => $request->rate_type === 'ppa' ? $language->ppa_rate : $language->ppw_rate
                        ]];
                    });

                    $user->languages()->sync($languages_temp->all());
                }
            }

        if($user && $registration) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return response()->json(['success' => true], 200);
    }

    public function register(RegistrationAccountRequest $request){
        $input = $request->except('c_password');

        // if buyer, check affiliate code
        if ($input['type'] === 'Buyer') {
            if ($input['affiliate_code']) {

                // find affiliate
                $affiliate = Registration::where('affiliate_code', $input['affiliate_code'])->first();

                if ($affiliate) {
                    $affiliate_user = User::where('email', $affiliate->email)->first();

                    if (!$affiliate_user) {
                        return response()->json([
                            "message" => 'Invalid affiliate code',
                            "errors" => [
                                "affiliate_code" => ['Invalid code. Affiliate not found.'],
                            ],
                        ],422);
                    } else {
                        // add affiliate_id
                        $input['affiliate_id'] = $affiliate_user->id;

                        // remove affiliate_code from input
                        unset($input['affiliate_code']);
                    }
                } else {
                    return response()->json([
                        "message" => 'Invalid affiliate code',
                        "errors" => [
                            "affiliate_code" => ['Invalid code. Affiliate not found.'],
                        ],
                    ],422);
                }
            }
        }

        if ($input['type'] === 'Seller') {
            $input['survey_code'] =  md5(uniqid(rand(), true));
        }

        // if writer default rate_type and writer_price
        if ($input['type'] === 'Writer') {
            $input['rate_type'] = 'ppw';
            $input['writer_price'] = '0.0085';
            $input['survey_code'] =  md5(uniqid(rand(), true));
        }

        $verification_code = md5(uniqid(rand(), true));
        $input['verification_code'] = $verification_code;
        $input['commission'] = 'yes';
        $input['credit_auth'] = 'No';
        $input['account_validation'] = 'processing';
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

        $registered = Registration::where('email', Auth::user()->email)->first();
        if ( isset($registered->is_sub_account) && $registered->is_sub_account == 1 ) {
            if ( isset($registered->team_in_charge) ) {
                $user_model = User::where('id', $registered->team_in_charge)->first();
                $user_id = isset($user_model->id) ? $user_model->id : Auth::user()->id;
            }
        }

        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user_id)->pluck('email');
        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');
        $UserId[] = $user_id;


        // $total_paid = Backlink::selectRaw('SUM(price) as total_paid')
        //                         ->where('status', 'Live')
        //                         ->where('payment_status', 'Paid')
        //                         ->where(function($query) use ($sub_buyer_ids, $UserId){
        //                             if(count($sub_buyer_ids) > 0) {
        //                                 return $query->whereIn('user_id', array_merge($sub_buyer_ids->toArray(),$UserId));
        //                             } else{
        //                                 return $query->whereIn('user_id', $UserId);
        //                             }
        //                         })
        //                         ->get();

        $total_purchased = Backlink::selectRaw('SUM(prices) as total_purchased')
//                                ->where('status', '!=', 'Canceled')
//                                ->where('status', '!=', 'To Be Validated')
                                ->whereIn('status', [
                                    'Live',
                                    'Processing',
                                    'Content In Writing',
                                    'Content Done',
                                    'Content sent',
                                    'Live in Process',
                                    'Issue',
                                    'Pending'
                                ])
                                ->where(function($query) use ($sub_buyer_ids, $UserId){
                                    if(count($sub_buyer_ids) > 0) {
                                        return $query->whereIn('user_id', array_merge($sub_buyer_ids->toArray(),$UserId));
                                    } else{
                                        return $query->whereIn('user_id', $UserId);
                                    }
                                })
                                ->get();

        $total_purchased_paid = Backlink::selectRaw('SUM(prices) as total_purchased_paid')
                                ->where('status', 'Live')
                                ->where('payment_status', 'Paid')
                                ->where(function($query) use ($sub_buyer_ids, $UserId){
                                    if(count($sub_buyer_ids) > 0) {
                                        return $query->whereIn('user_id', array_merge($sub_buyer_ids->toArray(),$UserId));
                                    } else{
                                        return $query->whereIn('user_id', $UserId);
                                    }
                                })
                                ->get();

        $wallet_transaction = WalletTransaction::selectRaw('SUM(amount_usd) as amount_usd')
                    ->where('user_id', $user_id)
                    ->whereIn('admin_confirmation', ['Paid', 'Refund Order'])
                    ->get();


        // refund coming from profile deposit
        $wallet_transaction_refunded = WalletTransaction::selectRaw('SUM(amount_usd) as amount_usd')
                    ->where('user_id', $user_id)
                    ->whereIn('admin_confirmation', ['Refunded'])
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
        } else {
            if ( isset($total_purchased[0]['total_purchased']) ) {
                $credit = $credit - floatval($total_purchased[0]['total_purchased']);
            }
        }

        if( isset($wallet_transaction_refunded[0]['amount_usd']) ) {
            $credit = $credit - floatval($wallet_transaction_refunded[0]['amount_usd']);
        }

        if( isset($total_purchased_paid[0]['total_purchased_paid']) && isset($wallet_transaction[0]['amount_usd']) ){
            $wallet = floatval($wallet_transaction[0]['amount_usd']) - floatval($total_purchased_paid[0]['total_purchased_paid']);
        }

        return [
            'wallet' => $wallet,
            'total_purchased' => floatVal($total_purchased[0]['total_purchased']),
            'total_purchased_paid' => floatVal($total_purchased_paid[0]['total_purchased_paid']),
            // 'total_paid' => floatVal($total_paid[0]['total_paid']),
            'credit' => round($credit),
            'deposit' => $deposit,
        ];
    }

    public function getTeamInCharge() {
        $team_in_charge = [4,5,6,7,1,8];

        $team = User::select('id','name', 'username', 'role_id')
            ->where('isOurs',0)
            ->whereIn('role_id', $team_in_charge)
            ->orderBy('username', 'asc');

        // if (Auth::user()->role_id === 6) {
        //     $team = $team->where(function ($sub) {
        //         $sub->where('id', Auth::id())
        //             ->orWhere('status', 'inactive');
        //     });
        // }

        $team = $team->get();

        return response()->json(['data'=> $team], 200);
    }

    public function getAffiliateList ()
    {
        $affiliates = User::select('id','name', 'username')
            ->where('isOurs', 1)
            ->whereStatus('active')
            ->whereIn('role_id', array(11))
            ->orderBy('username', 'asc')
            ->get();

        return response()->json(['data'=> $affiliates], 200);
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

        $team = User::select('id','name', 'username')
                    ->where('isOurs',0)
                    // ->where('role_id', $role_id)
                    ->where(function($query) use ($role_id, $request) {
                        if($request->role == 'Buyer') {
                            $query->whereIn('role_id', [5, 8]);
                        } else{
                            $query->where('role_id', $role_id);
                        }
                    })
                    ->where('status', '!=', 'inactive')
                    ->orderBy('username', 'asc')
                    ->get();
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
        $input['credit_auth'] = 'No';

        Registration::create($input);


        return response()->json(['success' => true], 200);
    }

    public function getSubAccount(Request $request) {
        $team_in_charge = $request->team_in_charge == '' ?  Auth::user()->id : $request->team_in_charge;
        $registration = Registration::select('registration.*', 'users.id as user_id')
            ->where('team_in_charge', $team_in_charge)
            ->join('users', 'users.email', '=', 'registration.email')
            ->where('is_sub_account', 1)
            ->get();

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
        $input = $request->only('username', 'name', 'status', 'credit_auth');
        $inputRegistration = $request->only('username', 'name', 'status', 'credit_auth', 'can_validate_backlink');

        $inputRegistration['is_show_price_basis'] = $request->is_show_price_basis == 'yes' ? 1:0;

        if ($request->password != '') {
            $request->validate([
                'c_password' => 'required|same:password'
            ]);

            $input['password'] = Hash::make($request->password);
            $inputRegistration['password'] = Hash::make($request->password);
        }

        $registration = Registration::findOrFail($request->id);
        $registration->update($inputRegistration);

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
            'password' => bcrypt($request->password),
            'reset_password_token' => null
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

        if( $request->account_validation != 'invalid' ) {
            $request->validate([
//                'writer_price' => 'required_if:type,==,Writer',
                'language_id' => 'required_if:type,==,Writer',
                'rate_type' => 'required_if:type,==,Writer',
                'id_payment_type' => 'required',
                'company_name' => 'required_if:company_type,==,Company',
                'writer_language_price_rates.*.rate' => 'required_if:type,==,Writer',
            ], [
                'writer_language_price_rates.*.required_if' =>  'The language price rate must not be empty if account type is Writer.',
            ]);
        }


        if (!$registered) {
            return response()->json(['success' => false], 422);
        }

        $registered->update([
            'verification_code' => null
        ]);

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

        // update registration and user account

        $inputs = $request->all();

        if(isset($request->language_id)) {
            $inputs['language_id'] = json_encode($request->language_id);
        }

        $this->accountRepository->updateAccount($inputs);

        return response()->json(['success' => true]);

    }

    public function updateMultipleInCharge(Request $request)
    {
        Registration::whereIn('id', $request->ids)->update([
            'team_in_charge' => $request->emp_id,
        ]);

        // notify in charge

        if (isset($request->emp_id) && !empty($request->emp_id) && !empty($request->user_ids)) {
            event(new TeamInChargeUpdatedEvent($request->emp_id, null, $request->user_ids));
        }

        return response()->json(['success' => true],200);
    }

    public function saveAffiliateCode (Request $request)
    {
        $affiliate = Registration::findOrFail($request->registration_id);

        $affiliate->update(['affiliate_code' => $request->code]);
    }

    public function getAffiliateCodeSet ()
    {
        $user_email = Auth::user()->email;
        $is_affiliate_code_set = false;

        if ($user_email) {
            if (Auth::user()->email) {
                $affiliate = Registration::select('affiliate_code')->where('email', $user_email)->value('affiliate_code');

                if ($affiliate) {
                    $is_affiliate_code_set = true;
                }
            }
        }

        return response()->json([
            'set' => $is_affiliate_code_set
        ]);
    }

    public function getAffiliateBuyers()
    {
        return Registration::where('affiliate_id', auth()->user()->id)
            ->where('status', 'active')
            ->with('user:id,email')
            ->paginate(10);
    }
}
