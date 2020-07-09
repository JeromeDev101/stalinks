<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Registration;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

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
        if($user->status === 'active'){
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

    public function edit(UpdateUserRequest $request) {
        $response = ['success' => false];
        $input = $request->except(
            'avatar',
            'countries_accessable',
            'created_at',
            'email_verified_at',
            'role',
            'security_work_mail',
            'user_type',
            'isAdmin',
            'id_payment_type',
            'wallet_transaction',
            'purchased'
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
                'skype' => $request->user_type['skype'],
                'company_name' => $request->user_type['company_name'],
                'username' => $request->username,
                'paypal_account' => $request->user_type['paypal_account'],
                'btc_account' => $request->user_type['btc_account'],
                'skrill_account' => $request->user_type['skrill_account'],
            ];

            $registered->update($dataRegistered);
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
