<?php

namespace App\Http\Requests;

use App\Rules\SecurePasswordRule;
use App\Rules\ValidateEmailRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCountryRequest extends FormRequest
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
                'max:255',
                Rule::unique('countries')->ignore($this->id),
            ],
            'code' => [
                'required',
                'max:255',
                Rule::unique('countries')->ignore($this->id),
            ],
        ];
    }
}
