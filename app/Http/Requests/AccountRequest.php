<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateEmailRule;
use App\Rules\SecurePasswordRule;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => [ 'required' ],
            'name' => [ 'required' ],
            'email' => [
                'unique:users,email',
                'required',
                'between:6,60',
                'email',
                new ValidateEmailRule,
            ],
            'phone' => [
                'nullable'
            ],
            'password' => [
                'required',
                new SecurePasswordRule(),
            ],
            'c_password' => [
                'required',
                'same:password'
            ],
            'type' => [
                'required'
            ],
            'company_name' => [
                'required_if:company_type,==,Company'
            ],
            'skype' => [
                'nullable'
            ],
            'commission' => [
                'required'
            ],
            'payment_email' => [
                'email',
                'nullable',
                new ValidateEmailRule,
            ],
            'payment_account' => [
                'nullable'
            ],
            'account_validation' => [
                'required'
            ],
            'id_payment_type' => [
                'required'
            ],
        ];
    }
}
