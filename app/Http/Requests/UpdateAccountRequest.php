<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateEmailRule;
use App\Rules\SecurePasswordRule;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

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
        $status = $this->status;
        $isSubAccount = $this->is_sub_account;
        $type = $this->type;


        return [
            'name' => [ 'required' ],
            'username' => [
                'required'
            ],
            'email' => [
                'required',
                'between:6,60',
                'email',
//                new ValidateEmailRule,
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
            // 'company_name' => [
            //     'required_if:company_type,==,Company'
            // ],
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
                Rule::requiredIf($isSubAccount == 0 && $status == 'active' && $type !== 'Affiliate'),

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
