<?php

namespace App\Http\Controllers;

use App\Models\MailDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class MailDraftController extends Controller
{
    public function index(Request $request) {
        $drafts = MailDraft::where('user_id', Auth::id());

        if (isset($request->search) && !empty($request->search)) {
            $drafts = $drafts->where(function ($query) use ($request) {
                $query->orWhere('mail_drafts.bcc', 'like', '%' . $request->search . '%')
                    ->orWhere('mail_drafts.subject', 'like', '%' . $request->search . '%')
                    ->orWhere('mail_drafts.body', 'like', '%' . $request->search . '%')
                    ->orWhere('mail_drafts.received', 'like', '%' . $request->search . '%');
            });
        }

        $drafts = $drafts->paginate($request->paginate);

        return $drafts;
    }

    public function store(Request $request) {
        $received = $this->getReceived($request->email);
        $bcc = $this->getReceived($request->bcc);
        $cc = $this->getReceived($request->cc);

        Auth::user()->drafts()->create([
            'cc' => $cc ?: null,
            'bcc' => $bcc ?: null,
            'body' => $request->content,
            'subject' => $request->title,
            'received' => $received ?: null,
            'mode' => strtolower($request->mode),
        ]);
    }

    public function destroy(Request $request) {
        MailDraft::find($request->id)->delete();
    }

    public function destroySelected(Request $request) {
        if (Gate::denies('delete-mails-drafts')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        MailDraft::whereIn('id', $request->ids)->delete();
    }

    protected function getReceived($email) {

        if (is_array($email)) {
            $emailArray = array_column($email, 'text');
        } else {
            if (strpos($email, '|') !== false) {
                $email = str_replace("|", ",", $email);
            }

            $emailArray = explode(',', $email);
        }

        $list_emails = array();

        foreach ($emailArray as $key => $value) {
            $list_emails[$key] = $value;
        }

        return implode(", ", $list_emails);
    }
}
