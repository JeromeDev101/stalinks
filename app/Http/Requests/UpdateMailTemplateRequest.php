<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMailTemplateRequest extends FormRequest
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
            'title' => [
                'required',
                'max:255',
                Rule::unique('mail_contents')->ignore($this->id),
            ],
            'content' => [
                'required'
            ],
            'country_id' => 'required|not_in:0',
        ];
    }
}
