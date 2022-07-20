<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsersPaymentType;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Registration;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * AuthController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Verify the request and generate tokens.
     *
     * @param  LoginRequest  $request
     * @return Response
     *
     * @throws Exception
     */
    public function login(LoginRequest $request)
    {
        $email = $this->getStatus($request->input('email'));
        $data = Config::get('services.passport') + [
            'username' => $email,
            'password' => $request->input('password'),
        ];
        $request = Request::create('/oauth/token', 'POST', $data);

        return App::handle($request);
    }

    private function getStatus($email)
    {
        $user = User::where('email', $email)->select('status')->first();
        if( isset($user->status) && $user->status === 'active'){
            $email = $email;
        }else{
            $email = 'invalid@email.address';
        }
        return $email;
    }

    public function register(RegisterRequest $request)
    {
        $input = $request->all();
        $input['avatar'] = '/images/noavatar.jpg';
        unset($input['c_password']);
        $input['password'] = Hash::make($input['password']);
        $userCreate = $this->userRepository->firstOrCreate($input);

        return response()->json(['success' => true, 'data' => $userCreate]);
    }

    // UpdateUserRequest
    public function edit(UpdateUserRequest $request) {

        $response = ['success' => false];
        $input_2 = $request->only('payment_type');
        $input = $request->except(
            'access',
            'avatar',
            'countries_accessable',
            'created_at',
            'email_verified_at',
            'role',
            'security_work_mail',
            'user_type',
            'isAdmin',
            'wallet_transaction',
            'purchased',
            'total_wallet',
            'registration',
            'isOurs',
            'work_mail_orig',
            'payment_type',
            'user_payment_types',
            'exam_duration',
            'bank_name',
            'account_name',
            'account_iban',
            'swift_code',
            'beneficiary_add'
        );


        unset($input['c_password']);
        unset($input['role']);

        if (isset($input['password']) && $input['password'] != '') {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        if (!isset($input['work_mail'])) {
            $input['work_mail'] = '';
        }

        if (!isset($input['work_mail_pass'])) {
            $input['work_mail_pass'] = '';
        }

        if (!isset($input['host_work_mail'])) {
            $input['host_work_mail'] = '';
        }

        //if (!isset($input['id_payment_type']) && ( isset($input['id_payment_type']) || is_null($input['id_payment_type']) )) {
        //   $input['id_payment_type'] = '';
      //  }

        $user = $this->userRepository->findById($input['id']);
        if (!$user) {
            return response()->json($response);
        }

        $this->userRepository->update($user, $input);

        if(isset($request->user_type)) {
            $registered = Registration::where('email', $input['email'])->first();

            $dataRegistered = [
                'skype' => $request->skype,
                'company_name' => $request->user_type['company_name'],
                'company_url' => $request->user_type['company_url'],
                'username' => $request->username,
                'paypal_account' => $request->user_type['paypal_account'],
                'btc_account' => $request->user_type['btc_account'],
                'skrill_account' => $request->user_type['skrill_account'],
                'is_freelance' => $request->user_type['company_type'] == 'Company' ? 0:1,
                'country_id' => $request->user_type['country_id'],
                'id_payment_type' => $request->id_payment_type,
                'writer_price' => $request->user_type['writer_price'],
            ];

            if( isset($input['password']) ){
                $dataRegistered['password'] = $input['password'];
            }

            $registered->update($dataRegistered);
        }

        // validate payment info
        if (isset($request->payment_type) && $request->payment_type && $user) {
            $request->validate([
                'payment_type.*' => 'unique:users_payment_type,account,' . $user->id . ',user_id'
            ], [
                'payment_type.*.unique' => 'Payment solution :input is already taken by another user.',
            ]);
        }

        // Insert users payments types
        $users_payment_type = UsersPaymentType::where('user_id', $input['id']);
        $users_payment_type->delete();

        $insert_input_users_payment_type = [];

        if ($input_2) {
            if(is_array($input_2['payment_type'])) {
                foreach($input_2['payment_type'] as $key => $types) {
                    if($types != '') {
                        array_push($insert_input_users_payment_type, [
                            'user_id' => $input['id'],
                            'payment_id' => $key,
                            'account' => $types,
                            'bank_name' => count($request->bank_name) > 0 ? json_encode($request->bank_name):null,
                            'account_name' => count($request->account_name) > 0 ? json_encode($request->account_name):null,
                            'account_iban' => count($request->account_iban) > 0 ? json_encode($request->account_iban):null,
                            'swift_code' => count($request->swift_code) > 0 ? json_encode($request->swift_code):null,
                            'beneficiary_add' => count($request->beneficiary_add) > 0 ? json_encode($request->beneficiary_add):null,
                            'is_default' => $key == $input['id_payment_type'] ? 1:0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }
            }
        }

        if (count($insert_input_users_payment_type)) {
            UsersPaymentType::insert($insert_input_users_payment_type);
        }

        $response['success'] = true;
        return response()->json($response);
    }

    /**
     * Revoke the access token from the authenticated user.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        return response()->json(['success' => $request->user()->token()->revoke()]);
    }


}
