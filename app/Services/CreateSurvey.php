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
     * @return string[]
     */
    public function rules()
    {
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
        Validator::make($data, $this->rules())
        ->validate();

        return true;
    }
}
