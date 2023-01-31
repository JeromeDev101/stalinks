<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Survey;
use App\Models\SurveyTwo;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Services\CreateSurvey;

class SurveyTwoController extends Controller
{
    public function store(Request $request)
    {
        return app(CreateSurvey::class)->execute($request->all());
    }

    public function getLastSurveySet()
    {
        $survey = SurveyTwo::latest()->first(['set']);

        return response()->json($survey);
    }

    public function hasUserAnsweredBothSurveys()
    {
        $first_set_a = Survey::where('user_id', auth()->user()->id)->where('set', 'a')->where('type', 'buyer')->first();
        $first_set_b = Survey::where('user_id', auth()->user()->id)->where('set', 'b')->where('type', 'buyer')->first();

        $second_set_a = SurveyTwo::where('user_id', auth()->user()->id)->where('set', 'a')->where('type', 'buyer')->first();
        $second_set_b = SurveyTwo::where('user_id', auth()->user()->id)->where('set', 'b')->where('type', 'buyer')->first();

        return response()->json(((
            $first_set_a && $first_set_b) 
            || ($second_set_a && $second_set_b) 
            || ($first_set_a && $second_set_b)
            || ($second_set_a && $first_set_b)
        ));
    }

    public function hasUserAnsweredSurveySet(Request $request)
    {
        if (isset($request->code) && $request->code) {
            $user_id = $this->getUserIdBySurveyCode($request->code);
        } else {
            $user_id = auth()->user()->id;
        }

        $firstSurvey = Survey::where('user_id', $user_id)
            ->where('set', $request->set)
            ->where('type', $request->type)
            ->first();

        $secondSurvey = SurveyTwo::where('user_id', $user_id)
            ->where('set', $request->set)
            ->where('type', $request->type)
            ->first();

        return $firstSurvey ?: $secondSurvey;
    }

    public function getList()
    {
        $survey = SurveyTwo::with(['user' => function ($query) {
            $query->select('name');
        }])->get();

        return response()->json($survey);
    }

    public function checkSurveyCode(Request $request)
    {
        $code = Registration::where('survey_code', $request->code)->first();

        return response()->json($code);
    }

    protected function getUserIdBySurveyCode($code)
    {
        $registration = Registration::where('survey_code', $code)->first();

        if ($registration) {

            $user = User::where('email', $registration->email)->first();

            if ($user) {

                return $user->id;

            } else {

                return null;

            }

        } else {

            return null;
        }
    }

    // Seller survey

    public function hasSellerAnsweredSurvey(Request $request)
    {
        if (isset($request->code) && $request->code) {
            $user_id = $this->getUserIdBySurveyCode($request->code);
        } else {
            $user_id = auth()->user()->id;
        }

        $firstSurvey = Survey::where('user_id', $user_id)->where('type', 'seller')->first();
        $secondSurvey = SurveyTwo::where('user_id', $user_id)->where('type', 'seller')->first();

        return $firstSurvey ?: $secondSurvey;
    }

    // Writer survey

    public function hasWriterAnsweredSurvey(Request $request)
    {
        if (isset($request->code) && $request->code) {
            $user_id = $this->getUserIdBySurveyCode($request->code);
        } else {
            $user_id = auth()->user()->id;
        }

        $firstSurvey = Survey::where('user_id', $user_id)->where('type', 'writer')->first();
        $secondSurvey = SurveyTwo::where('user_id', $user_id)->where('type', 'writer')->first();

        return $firstSurvey ?: $secondSurvey;
    }

    public function getSurveyQuestionFullDetails(Request $request)
    {
        $surveys = SurveyTwo::where('type', $request->type)
            ->where('set', $request->set)
            ->with('user')
            ->orderBy('created_at', 'DESC');

        if($request->pagination === 'All'){
            return response()->json([
                'data' => $surveys->get(),
                'total' => $surveys->count(),
            ],200);
        } else {
            return $surveys->paginate($request->pagination);
        }
    }
}
