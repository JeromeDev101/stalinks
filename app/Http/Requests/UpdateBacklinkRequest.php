<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBacklinkRequest extends FormRequest
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
//            'publisher_id' => 'required',
           // 'link' => 'required|url',
            'price' => 'required',
            'anchor_text' => 'required',
            // 'live_date' => 'required',
            'status' => 'required',
            'user_id' => 'required',
        ];
    }
}
