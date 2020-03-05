<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BacklinkRequest extends FormRequest
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
            'ext_domain_id' => 'required',
            'int_domain_id' => 'required',
            'price' => 'required',
        ];
    }
}
