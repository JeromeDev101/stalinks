<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModuleRequest extends FormRequest
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
                'group' => 'required',
                'page' => [
                    'required',
                    Rule::unique('modules')->where(function ($query) {
                        return $query->where('group', $this->input('group'))
                            ->where('page', $this->input('page'))
                            ->whereNull('deleted_at');
                    }),
                ],
            ];
        } else {
            return [
                'group' => 'required',
                'page' => [
                    'required',
                    Rule::unique('modules')->ignore($this->input('id'))->where(function ($query) {
                        return $query->where('group', $this->input('group'))
                            ->where('page', $this->input('page'))
                            ->whereNull('deleted_at');
                    })
                ],
            ];
        }
    }

    public function messages()
    {
        return [
            'group.required' => 'The module group field is required',
            'page.required' => 'The module page field is required',
            'page.unique' => "The group '" . $this->input('group') . "' and page '" . $this->input('page')
                . "' is already taken.",
        ];
    }
}
