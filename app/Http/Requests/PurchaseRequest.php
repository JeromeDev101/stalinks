<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                'invoice' => 'required|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,csv,xlsx,xls|max:2048',
                'receipt' => 'required|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,csv,xlsx,xls|max:2048'
            ];
        } else if ($this->method() === 'GET') {
            return [
                'purchase_from' => 'required',
                'purchase_amount' => 'required',
                'purchase_type_id' => 'required',
                'purchase_purchased_at' => 'required',
                'purchase_payment_type_id' => 'required',
                'purchase_receipt' => 'required|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,csv,xlsx,xls|max:2048',
                'purchase_invoice' => 'required|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,csv,xlsx,xls|max:2048'
            ];
        } else {
            return [
                'from' => 'required',
                'amount' => 'required',
                'type_id' => 'required',
                'purchased_at' => 'required',
                'payment_type_id' => 'required',
                'invoice' => 'nullable|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,csv,xlsx,xls|max:2048',
                'receipt' => 'nullable|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,csv,xlsx,xls|max:2048'
            ];
        }
    }

    public function messages()
    {
        if ($this->method() === 'GET') {
            return [
                'purchase_from.required' => 'The purchase from field is required.',
                'purchase_amount.required' => 'The price field is required.',
                'purchase_type_id.required' => 'The purchase type field is required.',
                'purchase_purchased_at.required' => 'The purchase date field is required.',
                'purchase_payment_type_id.required' => 'The payment via field is required.',
            ];
        } else {
            return [
                'from.required' => 'The purchase from field is required.',
                'amount.required' => 'The price field is required.',
                'type_id.required' => 'The purchase type field is required.',
                'purchased_at.required' => 'The purchase date field is required.',
                'payment_type_id.required' => 'The payment via field is required.',
            ];
        }
    }
}
