<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ToolRequest extends FormRequest
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
        if ($this->method() === 'POST') {
            return [
                'name' => [
                    'required',
                    Rule::unique('tools')->where(function ($query) {
                        return $query->where('deleted_at', null);
                    })
                ],
                'details' => 'required',
                'username' => 'required',
                'password' => 'required',
            ];
        } else {
            return [
                'name' => [
                    'required',
                    Rule::unique('tools')->ignore($this->input('id'))->where(function ($query) {
                        return $query->where('deleted_at', null);
                    })
                ],
                'details' => 'required',
                'username' => 'required',
                'password' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'The tool field is required.',
            'name.unique' => $this->input('name') . ' is already taken.',
        ];
    }
}
