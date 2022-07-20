<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseTypeRequest extends FormRequest
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
                    Rule::unique('purchase_types')->where(function ($query) {
                        return $query->where('purchase_category_id', $this->input('purchase_category_id'))
                            ->where('name', $this->input('name'));
                    }),
                ],
            ];
        } else {
            return [
                'name' => [
                    'required',
                    Rule::unique('purchase_types')->ignore($this->input('id'))->where(function ($query) {
                        return $query->where('purchase_category_id', $this->input('purchase_category_id'))
                            ->where('name', $this->input('name'));
                    })
                ],
            ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'The purchase type name field is required',
            'name.unique' => "Purchase type name '" . $this->input('name') . "' under purchase category '" . $this->input('category_name')
                . "' already exists.",
        ];
    }
}
