<?php

namespace App\Http\Controllers;

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
            'writer_exam.anchor_text',
            'writer_exam.link_to',
            'writer_exam.status as exam_status',
            'writer_exam.id as exam_id',
            'writer_exam.writer_id as exam_writer_id',
            'writer_exam.title',
            'writer_exam.content',
            'writer_exam.meta_description',
        ];

        $users = User::select($columns)
                        ->where('role_id', 4)
                        ->leftJoin('registration', 'users.email', '=', 'registration.email')
                        ->leftJoin('writer_exam', 'users.id', '=', 'writer_exam.writer_id')
                        ->where('users.isOurs', 1)
                        ->where('users.status', 'active')
                        ->where('registration.account_validation', 'valid')
                        ->when(isset($filters['status']), function($query) use ($filters) {
                            if($filters['status'] === 'Not Yet Setup') {
                                return $query->whereNull('writer_exam.status');
                            }else {
                                return $query->where('writer_exam.status', $filters['status']);
                            }
                        })
                        ->when(isset($filters['writer_id']), function($query) use ($filters) {
                            return $query->where('users.id', $filters['writer_id']);
                        })
                        ->when(isset($filters['anchor_text']), function($query) use ($filters) {
                            return $query->where('writer_exam.anchor_text', 'like', '%'.$filters['anchor_text'].'%');
                        })
                        ->orderBy('users.username')
                        ->get();
        

        return response()->json(['data' => $users],200);
    }

    public function update(Request $request) {
        $input = $request->except('created_at', 'updated_at', 'deleted_at', 'status');
        $input['status'] = 'For Checking';
        $exam = WriterExam::find($request->id);
        $exam->update($input);
    }

    public function checkExam(Request $request) {
        $input = $request->only('status', 'title');

        // update exam status
        $exam = WriterExam::find($request->id);
        $exam->update($input);

        // update registration
        $user = User::find($request->writer_id);
        if($user) {
            $registration = Registration::where('email', $user->email)->first();
            $registration->update(['is_exam_passed' => $input['status'] == 'Approved' ? 1:0 ]);
        }
    }

    public function store(Request $request) {
        $input = $request->except('writer_name');
        $input['status'] = 'Setup';
        WriterExam::create($input);

        return response()->json(['success' => true],200);
    }

    public function getExam(Request $request) {
        $exam = WriterExam::where('writer_id', $request->id)->first();
        return response()->json(['data' => $exam],200);
    }
}
