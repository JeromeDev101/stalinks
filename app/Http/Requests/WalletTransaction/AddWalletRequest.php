<?php

namespace App\Http\Requests\WalletTransaction;

use Illuminate\Foundation\Http\FormRequest;

class AddWalletRequest extends FormRequest
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
        $rules = [
            'payment_type' => 'required',
            'amount_usd' => 'required',
            'user_id_buyer' => 'required',
        ];

        if ($this->input('payment_type') != 1) {
            $rules = array_merge($rules, [
                'file' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
        }

        return $rules;
    }
}
