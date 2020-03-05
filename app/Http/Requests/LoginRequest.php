<?php

namespace App\Http\Requests;

use App\Rules\ValidateEmailRule;
use App\Rules\SecurePasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => [
                'required',
                'between:6,60',
                'email',
                new ValidateEmailRule,
            ],
            'password' => [
                'required'
            ],
        ];
    }
}
