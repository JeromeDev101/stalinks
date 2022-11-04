<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WriterPayRequest extends FormRequest
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
//        if ($this->request->get('id_payment_type') == 1) {
//            return [];
//        }

        return [
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
