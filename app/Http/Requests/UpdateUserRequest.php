<?php

namespace App\Http\Requests;

use App\Rules\ValidateEmailRule;
use App\Rules\SecurePasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'id' => [
                'required'
            ],
            'name' => [
                'required',
            ],
            'username' => [
                Rule::unique('users')->ignore($this->id),
            ],
            'work_mail' => [
                'nullable',
                'email',
                'max:255'
            ],
            'work_mail_pass' => [
                'nullable'
            ],
            'email' => [
                'required',
                'between:6,60',
                'email',
                Rule::unique('users')->ignore($this->id),
                new ValidateEmailRule,
            ],
            'phone' => [
                'nullable',
            ],
            'skype' => [
                'required',
            ],
            'password' => [
                new SecurePasswordRule(),
            ],
            'c_password' => [
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
