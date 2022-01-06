<?php

namespace App\Services;

use App\Models\Survey;
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
            'user_id' => 'unique:survey'
        ];
    }

    public function execute( array $data)
    {
        $data['user_id'] = auth()->user()->id;

        $this->validate($data);

        $survey = Survey::create($data);

        return $survey;
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
