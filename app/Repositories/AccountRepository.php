<?php

namespace App\Repositories;

use App\Events\UserValidateEvent;
use App\Models\User;
use App\Models\Registration;
use App\Models\UsersPaymentType;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class AccountRepository extends BaseRepository implements AccountRepositoryInterface
{

    protected $model;

    /**
     * BackLinkRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(Registration $model)
    {
        parent::__construct($model);
    }

    public function updateAccount($data)
    {

        $response['success'] = false;
        $input_2 = collect($data)->only('update_method_payment_type')->toArray();
        $input = collect($data)->except('company_type', 'user')->toArray();

        $input['is_freelance'] = $data['company_type'] == 'Freelancer' ? 1:0;

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

        if (isset($input['company_url']) && !empty($input['company_url'])) {
            $input['company_url'] = remove_http($input['company_url']);
        }

        // ---------------------------------------------------

        $user = User::where('email', $account->email)->first();

        if (isset($input['password']) && $input['password'] != '') {
            $user->update(['password' => $input['password']]);
        }

        if (isset($input['credit_auth']) && $input['credit_auth'] != '') {
            $user->update(['credit_auth' => $input['credit_auth']]);

            $emails = $user->subBuyers()->pluck('email');

            User::whereIn('email', $emails)->update([
                'credit_auth' => $input['credit_auth']
            ]);

            Registration::whereIn('email', $emails)->update([
                'credit_auth' => $input['credit_auth']
            ]);
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

        if (isset($input['account_validation']) && $input['account_validation'] != '') {
            if ($input['account_validation'] == 'invalid' || $input['account_validation'] == 'Invalid') {
                $user->update(['status' => 'inactive']);

                $input['status'] = 'inactive';
            }
        }

        if (isset($input['name']) && $input['name'] != '') {
            $user->update(['name' => $input['name']]);
        }

        if (isset($input['skype']) && $input['skype'] != '') {
            $user->update(['skype' => $input['skype']]);
        } else {
            $user->update(['skype' => 'none']);
        }

        if (isset($input['id_payment_type']) && $input['id_payment_type'] != '') {
            $user->update(['id_payment_type' => $input['id_payment_type']]);
        }

        // ---------------------------------------------------

        // send email - account validated

        if (isset($input['account_validation']) && $input['account_validation'] === 'valid') {
            if ($account->email_via !== 'account_validated') {
                event(new UserValidateEvent($input, $user));
                $input['email_via'] = 'account_validated';
            }
        } else if (isset($input['account_validation']) && $input['account_validation'] !== 'valid') {
            $input['email_via'] = null;
        }

        $account->update($input);

        $user = User::where('email', $input['email'])->first();

        $dataUser = [
            'username' => $data['username']
        ];

        if(!is_null($user)) {
            $user->update($dataUser);
        }

        // ---------------------------------------------------

        $users_payment_type = UsersPaymentType::where('user_id', $user->id);
        $users_payment_type->delete();

        // Insert users payments types
        $insert_input_users_payment_type = [];
        if(is_array($input_2['update_method_payment_type'])) {
            foreach($input_2['update_method_payment_type'] as $key => $types) {
                if($types != '') {
                    array_push($insert_input_users_payment_type, [
                        'user_id' => $user->id,
                        'payment_id' => $key,
                        'account' => $types,
                        'is_default' => $key == $input['id_payment_type'] ? 1:0,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                }
            }
        }

        UsersPaymentType::insert($insert_input_users_payment_type);

        $response['success'] = true;
        return response()->json($response);
    }
}
