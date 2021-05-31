<?php

namespace App\Http\Controllers;

use App\MailSignature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailSignatureController extends Controller
{
    public function getUsers()
    {
        return User::select('id', 'username', 'work_mail')
            ->distinct('username')
            ->where('isOurs', 0)
            ->where('status', 'active')
            ->where('work_mail', '!=', '')
            ->orderBy('username', 'asc')
            ->get();
    }

    public function getSignatures(Request $request)
    {
        $list = MailSignature::when($request->name, function($query) use ($request){
                return $query->where( 'name', 'like', "%".$request->name."%");
            })->when($request->user, function($query) use ($request){
                return $query->where( 'user_id', $request->user);
            })
            ->with('user')
            ->orderBy('id', 'desc');

        return $list->paginate($request->paginate);
    }

    public function storeSignature(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|unique:mail_signatures|max:255',
            'content' => 'required',
        ])->validate();

        $request['user_id'] = Auth::id();

        $sig = MailSignature::create($request->all());

        return response()->json(['success' => true, 'data' => $sig]);
    }

    public function storeSignatureImage(Request $request)
    {
        // $img_path = request()->file('originalName')->store('uploads', 'public');
        // return response()->json(['url' => "/storage/$img_path"]);

        $img_path = $request->file('upload')->store('uploads', 'public');
        return response()->json(['url' => "/storage/$img_path"]);
    }

    public function updateSignature(Request $request)
    {
        $sig = MailSignature::find($request->id);

        if (!$sig) {
            return response()->json(['success' => false]);
        }else{
            $sig->update($request->all());
            $response['success'] = true;
            return response()->json(['success' => true]);
        }
    }

    public function destroy($id)
    {
        MailSignature::find($id)->delete();

        return response()->json(['success' => true]);
    }
}
