<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
                'from' => 'required',
                'amount' => 'required',
                'type_id' => 'required',
                'purchased_at' => 'required',
                'payment_type_id' => 'required',
                'file' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ];
        } else {
            return [
                'from' => 'required',
                'amount' => 'required',
                'type_id' => 'required',
                'purchased_at' => 'required',
                'payment_type_id' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'from.required' => 'The purchase from field is required.',
            'amount.required' => 'The price field is required.',
            'type_id.required' => 'The purchase type field is required.',
            'purchased_at.required' => 'The purchase date field is required.',
            'payment_type_id.required' => 'The payment via field is required.',
        ];
    }
}
