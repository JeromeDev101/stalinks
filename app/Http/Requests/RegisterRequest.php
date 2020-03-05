<?php

namespace App\Http\Requests;

use App\Rules\ValidateEmailRule;
use App\Rules\SecurePasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => [
                'required'
            ],
            'work_mail' => [
                'nullable',
                'email',
                'max:255'
            ],
            'work_mail_pass' => [
                'required_with:work_mail'
            ],
            'email' => [
                'unique:users,email',
                'required',
                'between:6,60',
                'email',
                new ValidateEmailRule,
            ],
            'phone' => [
                'required'
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
                'required',
                'in:0,10'
            ],
            'role_id' => [
                'required'
            ]
        ];
    }
}
