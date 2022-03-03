<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Survey;
use App\Models\User;
use App\Services\CreateSurvey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function store(Request $request)
    {
        return app(CreateSurvey::class)->execute($request->all());
    }

    public function getLastSurveySet()
    {
        $survey = Survey::latest()->first(['set']);

        return response()->json($survey);
    }

    public function hasUserAnsweredBothSurveys()
    {
        $set_a = Survey::where('user_id', auth()->user()->id)->where('set', 'a')->where('type', 'buyer')->first();
        $set_b = Survey::where('user_id', auth()->user()->id)->where('set', 'b')->where('type', 'buyer')->first();

        return response()->json(($set_a && $set_b));
    }

    public function hasUserAnsweredSurveySet(Request $request)
    {
        if (isset($request->code) && $request->code) {
            $user_id = $this->getUserIdBySurveyCode($request->code);
        } else {
            $user_id = auth()->user()->id;
        }

        return Survey::where('user_id', $user_id)
            ->where('set', $request->set)
            ->where('type', $request->type)
            ->first();
    }

    public function getList()
    {
        $survey = Survey::with(['user' => function ($query) {
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

        return Survey::where('user_id', $user_id)->where('type', 'seller')->first();
    }

    // Writer survey

    public function hasWriterAnsweredSurvey(Request $request)
    {
        if (isset($request->code) && $request->code) {
            $user_id = $this->getUserIdBySurveyCode($request->code);
        } else {
            $user_id = auth()->user()->id;
        }

        return Survey::where('user_id', $user_id)->where('type', 'writer')->first();
    }

    public function getSurveyQuestionFullDetails(Request $request)
    {
        $surveys = Survey::where('type', $request->type)
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
