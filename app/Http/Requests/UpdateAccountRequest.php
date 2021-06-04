<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateEmailRule;
use App\Rules\SecurePasswordRule;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
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
            'name' => [ 'required' ],
             'username' => [
                 Rule::unique('registration')->ignore($this->id),
             ],
            'email' => [
                'required',
                'between:6,60',
                'email',
                new ValidateEmailRule,
                Rule::unique('registration')->ignore($this->id),
            ],
            'phone' => [
                'nullable'
            ],
            'password' => [
                new SecurePasswordRule(),
            ],
            'c_password' => [
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
                Rule::unique('registration')->ignore($this->id),
            ],
            'payment_account' => [
                'nullable'
            ],
            'account_validation' => [
                'required'
            ],
            'id_payment_type' => [
                'required_if:status,==,active'
            ],
            // 'writer_price' => [
            //     'required_if:type,==,Writer',
            //     'required_if:account_validation,==,valid'
            // ],
            // 'rate_type' => [
            //     'required_if:type,==,Writer'
            // ],
            // 'paypal_account' => [
            //     'required_if:id_payment_type,==,1'
            // ],
            // 'btc_account' => [
            //     'required_if:id_payment_type,==,3'
            // ],
            // 'skrill_account' => [
            //     'required_if:id_payment_type,==,2'
            // ],
        ];
    }
}
