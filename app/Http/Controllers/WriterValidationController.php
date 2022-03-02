<?php

namespace App\Http\Controllers;

use App\Events\WriterExamProcessedEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Registration;
use App\Models\WriterExam;

class WriterValidationController extends Controller
{
    public function getList(Request $request) {

        $filters = $request->all();

        $columns = [
            'users.*',
        ];

        $users = User::select($columns)
                        ->where('role_id', 4)
                        ->leftJoin('registration', 'users.email', '=', 'registration.email')
                        ->where('users.isOurs', 1)
                        ->where('users.status', 'active')
                        ->where('registration.account_validation', 'valid')
                        ->when(isset($filters['status']), function($query) use ($filters) {
                            if($filters['status'] === 'Not Yet Setup') {
                                return $query->has('writerExam', 0);
                            } else {
                                return $query->whereHas('writerExam', function($q) use ($filters) {
                                    $q->where('writer_exam.status', $filters['status']);
                                });
                            }
                        })
                        ->when(isset($filters['writer_id']), function($query) use ($filters) {
                            return $query->where('users.id', $filters['writer_id']);
                        })
                        ->when(isset($filters['anchor_text']), function($query) use ($filters) {
                            return $query->whereHas('writerExam', function($q) use ($filters) {
                                $q->where('writer_exam.anchor_text', 'like', '%'. $filters['anchor_text'].'%');
                            });
                        })
                        ->with('writerExam')
                        ->orderBy('users.created_at', 'desc')
                        ->get();

        if($filters['paginate'] === 'All'){
            return response()->json([
                'data' => $users->get(),
                'total' => $users->count(),
            ],200);
        } else {
            return $users->paginate($filters['paginate']);
        }


        return response()->json(['data' => $users],200);
    }

    public function update(Request $request) {
        $input = $request->except('created_at', 'updated_at', 'deleted_at', 'status');
        $input['status'] = 'For Checking';
        $exam = WriterExam::find($request->id);
        $exam->update($input);

        event(new WriterExamProcessedEvent($exam, 'checking'));
    }

    public function checkExam(Request $request) {
        $input = $request->only('status', 'attempt');

        // update exam status
        $exam = WriterExam::find($request->id);

        if ($input['status'] != $exam->status) {
            if ($input['status'] === 'Approved' || $input['status'] === 'Disapproved') {
                event(new WriterExamProcessedEvent($exam, strtolower($input['status'])));
            }
        }

        $exam->update($input);

        // update registration
        $user = User::find($request->writer_id);
        if($user) {
            $registration = Registration::where('email', $user->email)->first();

            if ($input['status'] === 'Disapproved') {
                // deactivate account if 2nd attempt
                if ($input['attempt'] === 2) {
                    $user->update(['status' => 'inactive']);
                    $registration->update(['is_exam_passed' => 0, 'account_validation' => 'invalid', 'status' => 'inactive']);
                } else {
                    $user->update(['exam_second_attempt_at' => Carbon::now()->addWeeks(3)->format('Y-m-d')]);
                }
            } else {
                if ($input['status'] === 'Approved') {

                    $user->update(['status' => 'active']);

                    if ($registration->survey_code === null) {
                        $registration->update([
                            'is_exam_passed' => 1,
                            'account_validation' => 'valid',
                            'status' => 'active',
                            'survey_code' => md5(uniqid(rand(), true))
                        ]);
                    } else {
                        $registration->update([
                            'is_exam_passed' => 1,
                            'account_validation' => 'valid',
                            'status' => 'active',
                        ]);
                    }
                }

                $registration->update(['is_exam_passed' => $input['status'] == 'Approved' ? 1:0 ]);
            }

        }
    }

    public function store(Request $request) {
        $input = $request->except('writer_name', 'duration');
        $input['status'] = 'Setup';
        $exam = WriterExam::create($input);

        event(new WriterExamProcessedEvent($exam, 'setup'));

        return response()->json(['success' => true],200);
    }

    public function getExamDetails(Request $request) {
        $exam = WriterExam::where('writer_id', $request->id)->where('attempt', $request->attempt)->first();

        return response()->json(['data' => $exam],200);
    }
}
