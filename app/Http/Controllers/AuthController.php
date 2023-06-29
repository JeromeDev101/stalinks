<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UsersPaymentType;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Rules\PaymentInfoExists;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Registration;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
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
            'beneficiary_add',
            'account_holder',
            'account_type',
            'routing_num',
            'wire_routing_num',
            'languages',
            'sub_buyers_count',
            'img_paths',
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

        if (!isset($input['work_mail_display_name'])) {
            $input['work_mail_display_name'] = '';
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

            $writer_price = $request->user_type['writer_price'];

            if ($request->user_type['type'] === 'Writer') {
                if($request->user_type['rate_type'] == 'ppw') {
                    $writer_price = '0.0085';
                } else {
                    $writer_price = '10';
                }
            }

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
                'rate_type' => $request->user_type['rate_type'],
                'writer_price' => $writer_price,
            ];

            if( isset($input['password']) ){
                $dataRegistered['password'] = $input['password'];
            }

            $registered->update($dataRegistered);
        }

        // temporarily removed as of boss - 02-07-2023
        // validate payment info
//         if (isset($request->payment_type) && $request->payment_type && $user) {
//             $request->validate([
// //                'payment_type.*' => 'unique:users_payment_type,account,' . $user->id . ',user_id'
//                 'payment_type.*' => [new PaymentInfoExists($user->id)],
//             ], [
//                 'payment_type.*.unique' => 'Payment solution :input is already taken by another user.',
//             ]);
//         }

        // Insert users payments types
//        $users_payment_type = UsersPaymentType::where('user_id', $input['id']);
//        $users_payment_type->delete();

        $insert_input_users_payment_type = [];

        if ($input_2) {
            if(is_array($input_2['payment_type'])) {
                foreach($input_2['payment_type'] as $key => $types) {
                    if($types != '') {
                        $img_path = $request->img_paths[$key];

                        array_push($insert_input_users_payment_type, [
                            'user_id' => $input['id'],
                            'payment_id' => $key,
                            'account' => $types,
                            'bank_name' => count($request->bank_name) > 0 ? json_encode($request->bank_name) : null,
                            'account_name' => count($request->account_name) > 0 ? json_encode($request->account_name) : null,
                            'account_iban' => count($request->account_iban) > 0 ? json_encode($request->account_iban): null,
                            'swift_code' => count($request->swift_code) > 0 ? json_encode($request->swift_code): null,
                            'beneficiary_add' => count($request->beneficiary_add) > 0 ? json_encode($request->beneficiary_add): null,
                            'account_holder' => count($request->account_holder) > 0 ? json_encode($request->account_holder): null,
                            'account_type' => count($request->account_type) > 0 ? json_encode($request->account_type): null,
                            'routing_num' => count($request->routing_num) > 0 ? json_encode($request->routing_num): null,
                            'wire_routing_num' => count($request->wire_routing_num) > 0 ? json_encode($request->wire_routing_num): null,
                            'is_default' => $key == $input['id_payment_type'] ? 1:0,
                            'img_path' => $img_path,
                        ]);
                    }
                }
            }
        }

        if (count($insert_input_users_payment_type)) {
            foreach ($insert_input_users_payment_type as $insert) {
                UsersPaymentType::firstOrCreate($insert);
            }

            // delete payment info that is not included anymore

            // get all ids
            $all_ids = UsersPaymentType::where('user_id', $user->id)->get()->pluck('id')->toArray();

            // get existing ids
            $existing_ids = [];

            foreach ($insert_input_users_payment_type as $insert) {
                $temp = UsersPaymentType::select('id')->where($insert)->first();

                if ($temp) {
                    $existing_ids[] = $temp->id;
                }
            }

            // get difference
            $diff_ids = array_diff($all_ids, $existing_ids);

            // delete items
            if (count($diff_ids)) {
                foreach ($diff_ids as $diff) {
                    $delete = UsersPaymentType::find($diff);

                    $delete->delete();
                }
            }
        }

        $response['success'] = true;
        return response()->json($response);
    }

    public function updateUserPaymentTypeImage (Request $request) {
        $file = $request->file('file');

        // check if payment method already exist
        $payment_type = UsersPaymentType::where('user_id', $request->user_id)
            ->where('account', $request->account)
            ->where('payment_id', $request->payment_id)
            ->whereNull('deleted_at')
            ->first();

        // save image
        $folder = 'user_payment_images';

        $extension = $file->getClientOriginalExtension();
        $fileName = 'image_' . date('Ymd_His') . '_' . uniqid() . '.' . $extension;

        $path = $file->storeAs($folder, $fileName, 'public');

        if ($payment_type) {
            // update payment type
            $payment_type->update(['img_path' => $path]);
        } else {
            // add payment type
            $payment_type_insert = [
                'user_id' => $request->user_id,
                'payment_id' => $request->payment_id,
                'account' => $request->account,
                'bank_name' => count($request->bank_name) > 0 ? json_encode($request->bank_name) : null,
                'account_name' => count($request->account_name) > 0 ? json_encode($request->account_name) : null,
                'account_iban' => count($request->account_iban) > 0 ? json_encode($request->account_iban): null,
                'swift_code' => count($request->swift_code) > 0 ? json_encode($request->swift_code): null,
                'beneficiary_add' => count($request->beneficiary_add) > 0 ? json_encode($request->beneficiary_add): null,
                'account_holder' => count($request->account_holder) > 0 ? json_encode($request->account_holder): null,
                'account_type' => count($request->account_type) > 0 ? json_encode($request->account_type): null,
                'routing_num' => count($request->routing_num) > 0 ? json_encode($request->routing_num): null,
                'wire_routing_num' => count($request->wire_routing_num) > 0 ? json_encode($request->wire_routing_num): null,
                'is_default' => $request->payment_id == $request->payment_default ? 1 : 0,
                'img_path' => $path
            ];

            UsersPaymentType::create($payment_type_insert);
        }

        return response()->json(['success' => true, 'data' => $path]);
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
