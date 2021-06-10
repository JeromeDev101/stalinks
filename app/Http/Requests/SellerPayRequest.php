<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerPayRequest extends FormRequest
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
        // Disregard validation if payment type is paypal
        if ($this->request->get('payment_id') == 1) {
            return [];
        }

        return [
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
