<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use App\Rules\DomainRule;
use Illuminate\Foundation\Http\FormRequest;

class DomainProviderRequest extends FormRequest
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
            'name' => ['required', new DomainRule($this->username)],
            'username' => 'required',
            'password' => 'required',
        ];
    }
}
