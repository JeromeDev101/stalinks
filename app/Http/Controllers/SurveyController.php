<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Services\CreateSurvey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function store(Request $request)
    {
        $survey = app(CreateSurvey::class)->execute($request->all());

        return $survey;
    }

    public function getLastSurveySet()
    {
        $survey = Survey::latest()->first(['set']);

        return response()->json($survey);
    }

    public function hasUserAnsweredSurvey()
    {
        $survey = Survey::where('user_id', auth()->user()->id)->first();

        return $survey;
    }
}
