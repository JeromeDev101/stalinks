<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\RegistrationAccountRequest;
use App\Http\Requests\UpdateSetPasswordRequest;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailVerification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class AccountController extends Controller
{

    public function store(AccountRequest $request)
    {
        $input = $request->all();
        unset($input['c_password']);
        $input['password'] = Hash::make($input['password']);
        $registration = Registration::create($input);

        // duplicate the record in Users Table
        $data['name'] = $registration['name'];
        $data['email'] = $registration['email'];
        $data['phone'] = $registration['phone'];
        $data['avatar'] = '/images/noavatar.jpg';
        $data['role_id'] = 4;
        $data['type'] = 0;
        $data['isOurs'] = 1;
        $data['password'] = $input['password'];
        User::create($data);

        return response()->json(['success' => true, 'data' => $registration], 200);
    }

    public function getList(Request $request)
    {
        $status = $request->status;
        $type = $request->type;
        $search = $request->search;

        $list = Registration::when( $status, function($query) use ($status){
            return $query->where( 'status', $status );
        })->when( $type, function($query) use ($type){
            return $query->where( 'type', $type );
        })->when( $search, function($query) use ($search){
            return $query->where( 'name', 'LIKE', '%'.$search.'%' )
                ->orWhere( 'email', 'LIKE', '%'.$search.'%' );
        })->get();
        

        return response()->json([
            'data' => $list
        ],200);
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
        
        if (isset($input['password']) && $input['password'] != '') {
            $user_data['password'] = $input['password'];
            $user = User::where('email', $account->email);
            $user->update($user_data);
        }

        if (isset($input['credit_auth']) && $input['credit_auth'] != '') {
            $user = User::where('email', $account->email);
            $user->update(['credit_auth' => $input['credit_auth']]);
        }
            
        $account->update($input);
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

        // duplicate the record in Users Table
        $data['name'] = $registration['name'];
        $data['email'] = $registration['email'];
        $data['phone'] = $registration['phone'];
        $data['avatar'] = '/images/noavatar.jpg';
        $data['role_id'] = 4;
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
}
