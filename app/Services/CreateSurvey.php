<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CreateSurvey
{
    /**
     * Get the validation rules for the service.
     *
     * @param $data
     * @return string[]
     */
    public function rules($data)
    {
        if ($data['set'] === 'a' && $data['type'] === 'buyer') {
            return [
                'one' => 'required',
                'one_other' => 'required_if:one,==,no',
                'two' => 'required',
                'two_other' => 'required_if:two,==,no',
                'three' => 'required',
                'three_other' => 'required_if:three,==,no',
                'four' => 'required',
                'four_other' => 'required_if:four,==,yes',
                'five' => 'required',
                'five_other' => 'required_if:five,==,no',
                'user_id' => 'required'
            ];
        } else if ($data['set'] === 'b' && $data['type'] === 'buyer') {
            return [
                'one' => 'required',
                'one_other' => 'required_if:one,==,other',
                'two' => 'required',
                'three' => 'required',
                'four' => 'required',
                'four_other' => 'required_if:four,==,other',
                'five' => 'required',
                'six' => 'required',
                'six_other' => 'required_if:six,==,Others',
                'user_id' => 'required'
            ];
        } else if ($data['set'] === 'a' && $data['type'] === 'seller') {
            return [
                'one' => 'required',
                'one_other' => 'required_if:one,==,no',
                'two' => 'required',
                'two_other' => 'required_if:two,==,no',
                'three' => 'required',
                'three_other' => 'required_if:three,==,yes',
                'four' => 'required',
                'four_other' => 'required_if:four,==,no',
                'five' => 'required',
                'six' => 'required',
                'seven' => 'required',
                'seven_other' => 'required_if:seven,==,Others',
                'user_id' => 'required'
            ];
        } else if ($data['set'] === 'a' && $data['type'] === 'writer') {
            return [
                'one' => 'required',
                'one_other' => 'required_if:one,==,no',
                'two' => 'required',
                'two_other' => 'required_if:two,==,no',
                'three' => 'required',
                'three_other' => 'required_if:three,==,yes',
                'four' => 'required',
                'five' => 'required',
                'six' => 'required',
                'six_other' => 'required_if:six,==,Others',
                'user_id' => 'required'
            ];
        } else {
            return [
                'one' => 'required',
                'two' => 'required',
                'three' => 'required',
                'four' => 'required',
                'five' => 'required',
                'six' => 'required',
                'seven' => 'required_if:type,==,seller',
                'user_id' => 'required'
            ];
        }
    }

    public function messages()
    {
        return [
            'one.required' => 'Please specify an answer for question one.',
            'two.required' => 'Please specify an answer for question two.',
            'three.required' => 'Please specify an answer for question three.',
            'four.required' => 'Please specify an answer for question four.',
            'five.required' => 'Please specify an answer for question five.',
            'six.required' => 'Please specify an answer for question six.',
            'seven.required' => 'Please specify an answer for question seven.',

            'one_other.required_if' => 'Please specify an answer to the input box if question one answer is ":value"',
            'two_other.required_if' => 'Please specify an answer to the input box if question two answer is ":value"',
            'three_other.required_if' => 'Please specify an answer to the input box if question three answer is ":value"',
            'four_other.required_if' => 'Please specify an answer to the input box if question four answer is ":value"',
            'five_other.required_if' => 'Please specify an answer to the input box if question five answer is ":value"',
            'six_other.required_if' => 'Please specify an answer to the input box if question six answer is ":value"',
            'seven_other.required_if' => 'Please specify an answer to the input box if question seven answer is ":value"',
        ];
    }

    public function execute( array $data)
    {
        if (isset($data['code']) && $data['code']) {
            // get user id
            $registration = Registration::where('survey_code', $data['code'])->first();

            if ($registration) {

                $user = User::where('email', $registration->email)->first();

                if ($user) {

                    $data['user_id'] = $user->id;

                } else {

                    return response()->json([
                        "message" => 'Invalid survey code. User not found.',
                        "errors" => [
                            "survey_code" => ['Invalid code. User not found.'],
                        ],
                    ],422);

                }

            } else {

                return response()->json([
                    "message" => 'Invalid survey code. User not found.',
                    "errors" => [
                        "survey_code" => ['Invalid code. User not found.'],
                    ],
                ],422);

            }

        } else {
            $data['user_id'] = auth()->user()->id;
        }

        $this->validate($data);

        return Survey::create($data);
    }

    /**
     * Validate all data to execute the service.
     *
     * @param array $data
     * @return bool
     */
    public function validate( array $data) : bool
    {
        Validator::make($data, $this->rules($data), $this->messages())
        ->validate();

        return true;
    }
}
