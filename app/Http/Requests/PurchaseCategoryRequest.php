<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseCategoryRequest extends FormRequest
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
                    Rule::unique('purchase_categories')->where(function ($query) {
                        return $query->where('name', $this->input('name'));
                    }),
                ],
            ];
        } else {
            return [
                'name' => [
                    'required',
                    Rule::unique('purchase_categories')
                        ->ignore($this->input('id'))
                        ->where(function ($query) {
                            return $query->where('name', $this->input('name'));
                        })
                ],
            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'The purchase category name field is required',
            'name.unique' => "Purchase category name '" . $this->input('name') . "' already exists.",
        ];
    }
}
