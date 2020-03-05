<?php

namespace App\Http\Requests;


use App\Rules\ValidateUrlRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class HostingRequest extends FormRequest
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
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'link' => [
                'required',
                'url',
                'unique:hosting_providers,link,' . $this->id,
                ]

        ];
    }
}
