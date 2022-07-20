<?php

namespace App\Repositories;

use App\Events\UserUnvalidatedEvent;
use App\Events\UserValidateEvent;
use App\Models\User;
use App\Models\Registration;
use App\Models\UsersPaymentType;
use App\Events\TeamInChargeUpdatedEvent;
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
        $input = collect($data)->except('company_type', 'user', 'isVerified', 'bank_name', 'account_name', 'account_iban', 'swift_code', 'beneficiary_add')->toArray();

        $input['is_freelance'] = $data['company_type'] == 'Freelancer' ? 1:0;
        $input['is_show_price_basis'] = $data['is_show_price_basis'] == 'yes' ? 1:0;

        unset($input['c_password']);

        if (isset($input['password']) && $input['password'] != '') {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $account = Registration::find($input['id']);
        if (!$account) {
            return response()->json($response);
        } else {
            // if writer
            if($input['type'] == 'Writer') {
                if($input['rate_type'] == 'ppw') {
                    $inputs['writer_price'] = '0.02';
                } else {
                    $inputs['writer_price'] = '12';
                }
            }

            if(isset($inputs['writer_price'])) {
                $account->update($inputs);
            }

            if(isset($input['affiliate'])) {
                $account->update(['affiliate_id' => $input['affiliate']]);
            }

        }

        if (isset($input['company_url']) && !empty($input['company_url'])) {
            $input['company_url'] = remove_http($input['company_url']);
        }

        // ---------------------------------------------------

        $user = User::where('email', $account->email)->first();

        if (isset($input['password']) && $input['password'] != '') {
            if ($user) {
                $user->update(['password' => $input['password']]);
            }
        }

        if (isset($input['credit_auth']) && $input['credit_auth'] != '') {
            if ($user) {
                $user->update(['credit_auth' => $input['credit_auth']]);

                $emails = $user->subBuyers()->pluck('email');

                User::whereIn('email', $emails)->update([
                    'credit_auth' => $input['credit_auth']
                ]);

                Registration::whereIn('email', $emails)->update([
                    'credit_auth' => $input['credit_auth']
                ]);
            }
        }

        if (isset($input['email']) && $input['email'] != '') {
            if ($user) {
                $user->update(['email' => $input['email']]);
            }
        }

        if (isset($input['username']) && $input['username'] != '') {
            if ($user) {
                $user->update(['username' => $input['username']]);
            }
        }

        if (isset($input['status']) && $input['status'] != '') {
            if ($user) {
                $user->update(['status' => $input['status']]);
            }
        }

        if (isset($input['account_validation']) && $input['account_validation'] != '') {
            if ($input['account_validation'] == 'invalid' || $input['account_validation'] == 'Invalid') {
                if ($user) {
                    $user->update(['status' => 'inactive']);
                }

                $input['status'] = 'inactive';
            }
        }

        if (isset($input['name']) && $input['name'] != '') {
            if ($user) {
                $user->update(['name' => $input['name']]);
            }
        }

        if (isset($input['skype']) && $input['skype'] != '') {
            if ($user) {
                $user->update(['skype' => $input['skype']]);
            }
        } else {
            if ($user) {
                $user->update(['skype' => 'none']);
            }
        }

        if (isset($input['id_payment_type']) && $input['id_payment_type'] != '') {
            if ($user) {
                $user->update(['id_payment_type' => $input['id_payment_type']]);
            }
        }

        // change user role_id according to type
        if (isset($input['type']) && $input['type'] != '') {

            $user_roles = [
                'Writer' => 4,
                'Buyer' => 5,
                'Seller' => 6,
                'Affiliate' => 11
            ];

            if ($user) {
                $user->update(['role_id' => $user_roles[$input['type']]]);
            }
        }

        // ---------------------------------------------------

        // send email - account validated

        if (isset($input['account_validation']) && $input['account_validation'] === 'valid') {
            if ($account->email_via !== 'account_validated' && $account->type !== 'Affiliate') {

                if ($user) {
                    event(new UserValidateEvent($input, $user));
                }

                $input['email_via'] = 'account_validated';
                $input['validation_reminded_at'] = null;
                $input['reminded_days'] = null;

                if ($account->email_via_others === 'account_invalidated') {
                    $input['email_via_others'] = null;
                }
            }
        } else if (isset($input['account_validation'])
            && $input['account_validation'] !== 'valid'
            && $account->email_via === 'account_validated') {
            $input['email_via'] = null;
        }

        // ---------------------------------------------------

        // notify team in charge if set

        if (isset($input['team_in_charge']) && !empty($input['team_in_charge'])) {
            if ($account->team_in_charge !== $input['team_in_charge']) {
                if ($user) {
                    event(new TeamInChargeUpdatedEvent($input['team_in_charge'], $user));
                }
            }
        }

        // ---------------------------------------------------

        // send email - account inactive

        if (isset($input['status']) && $input['status'] === 'inactive') {
            if ($account->email_via_others !== 'account_invalidated') {

                if ($user) {
                    event(new UserUnvalidatedEvent($input, $user));
                }

                $input['email_via_others'] = 'account_invalidated';
                $input['validation_reminded_at'] = null;
                $input['email_via'] = null;
                $input['deposit_reminded_at'] = null;
                $input['reminded_days'] = null;
            }
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

        if ($user) {
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
                            'bank_name' => count($data['bank_name']) > 0 ? json_encode($data['bank_name']):null,
                            'account_name' => count($data['account_name']) > 0 ? json_encode($data['account_name']):null,
                            'account_iban' => count($data['account_iban']) > 0 ? json_encode($data['account_iban']):null,
                            'swift_code' => count($data['swift_code']) > 0 ? json_encode($data['swift_code']):null,
                            'beneficiary_add' => count($data['beneficiary_add']) > 0 ? json_encode($data['beneficiary_add']):null,
                            'is_default' => $key == $input['id_payment_type'] ? 1:0,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }
            }

            if (count($insert_input_users_payment_type)) {
                UsersPaymentType::insert($insert_input_users_payment_type);
            }
        }

        $response['success'] = true;
        return response()->json($response);
    }
}
