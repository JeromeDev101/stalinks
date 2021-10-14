<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Registration;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Support\Facades\Hash;


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
            $input['company_url'] = $this->remove_http($input['company_url']);
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

        $account->update($input);

        $user = User::where('email', $input['email'])->first();

        $dataUser = [
            'username' => $data['username']
        ];

        if(!is_null($user)) {
            $user->update($dataUser);
        }

        $response['success'] = true;
        return response()->json($response);
    }

    public function remove_http($url) {
        $disallowed = array('http://', 'https://', 'www.');
        foreach($disallowed as $d) {
            if(strpos($url, $d) === 0) {
                return str_replace($d, '', $url);
            }
        }
        return $url;
    }
}
