<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateEmailRule;
use Illuminate\Validation\Rule;
use App\Rules\SecurePasswordRule;

class RegistrationAccountRequest extends FormRequest
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
                'required',
                Rule::unique('registration')->ignore($this->id),
                Rule::unique('users')->ignore($this->id),
            ],
            'email' => [
                'unique:users,email',
                'required',
                'between:6,60',
                'email:strict',
                new ValidateEmailRule,
                Rule::unique('registration')->ignore($this->id),
                Rule::unique('users')->ignore($this->id),
            ],
            'type' => [
                'required',
                Rule::in(['Seller', 'Buyer', 'Writer', 'Affiliate']),
            ],
            'password' => [
                'required',
                new SecurePasswordRule(),
            ],
            'c_password' => [
                'required',
                'same:password'
            ],
        ];
    }
}
