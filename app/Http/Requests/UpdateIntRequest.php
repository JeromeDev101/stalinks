<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateIntRequest extends FormRequest
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
            'domain' => [
                'required',
                'url',
                'max:255',
                Rule::unique('int_domains')->ignore($this->id),
            ],
            'country_id' => [
                'required',
                'integer',
                'not_in:0'
            ],
            'domain_provider_id' => [
                'required',
                'integer',
                'not_in:0'
            ],
            'hosting_provider_id' => [
                'required',
                'integer',
                'not_in:0'
            ],
        ];
    }
}
