<?php

namespace App\Http\Controllers;

use App\Models\MailAutoReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MailAutoReplyController extends Controller
{
    public function index (Request $request) {
        $auto = MailAutoReply::where('user_id', Auth::id());

        if (isset($request->search) && !empty($request->search)) {
            $auto = $auto->where(function ($query) use ($request) {
                $query->orWhere('mail_auto_replies.subject', 'like', '%' . $request->search . '%')
                    ->orWhere('mail_auto_replies.body', 'like', '%' . $request->search . '%');
            });
        }

        if (isset($request->show) && $request->show) {
            $auto = $auto->where('active', 1);
        }

        return $auto->paginate($request->paginate);
    }

    public function store (Request $request) {
        if (Gate::denies('create-mails-auto-replies')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $this->validateRequest($request);

        $auto = Auth::user()->autoReplies()->create([
            'subject' => $request->subject,
            'body' => $request->body
        ]);

        return response()->json(['success' => true, 'data' => $auto]);
    }

    public function update (Request $request) {
        if (Gate::denies('update-mails-auto-replies')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $this->validateRequest($request);

        $auto = MailAutoReply::find($request->id);

        if (!$auto) {
            return response()->json(['success' => false]);
        }else{
            $auto->update($request->except('id'));
            return response()->json(['success' => true]);
        }
    }

    public function destroy (Request $request) {
        if (Gate::denies('delete-mails-auto-replies')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        MailAutoReply::whereIn('id', $request->ids)->delete();
    }

    public function toggleAutoReply (Request $request) {

        if ($request->checked) {
            MailAutoReply::where('active', 1)->update(['active' => 0]);

            MailAutoReply::where('id', $request->id)->update(['active' => 1]);
        } else {
            MailAutoReply::where('id', $request->id)->update(['active' => 0]);
        }

    }

    public function getAutoReplyState () {
        $active = MailAutoReply::where('user_id', Auth::id())->where('active', 1)->count();

        return response()->json([
            'active' => $active > 0
        ]);
    }

    protected function validateRequest ($request) {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
        ]);
    }
}
