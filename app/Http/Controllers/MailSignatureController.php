<?php

namespace App\Http\Controllers;

use App\MailSignature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MailSignatureController extends Controller
{
    public function getUsers()
    {
        return User::select('id', 'username', 'work_mail')
            ->distinct('work_mail')
            ->where('isOurs', 0)
            ->where('status', 'active')
            ->where('work_mail', '!=', '')
            ->orderBy('username', 'asc')
            ->get()
            ->unique('work_mail')
            ->values()
            ->all();
    }

    public function getSignatures(Request $request)
    {
        $list = MailSignature::when($request->name, function($query) use ($request){
                return $query->where( 'name', 'like', "%".$request->name."%");
            })->when($request->work_mail, function($query) use ($request){
                return $query->where( 'work_mail', $request->work_mail);
            })
            ->with('user')
            ->orderBy('id', 'desc');

        return $list->paginate($request->paginate);
    }

    public function storeSignature(Request $request)
    {
        Validator::make($request->all(), [
            'content' => 'required',
            'work_mail' => [
                'required',
                Rule::unique('mail_signatures')->where(function ($query) {
                    return $query->where('deleted_at', null);
                })
            ],
            'name' => [
                'max:225',
                'required',
                Rule::unique('mail_signatures')->where(function ($query) {
                    return $query->where('deleted_at', null);
                })
            ]
        ],
        [
            'work_mail.unique' => 'Only one email signature is allowed per work mail/login mail.'
        ])->validate();

        $request['user_id'] = Auth::id();

        $sig = MailSignature::create($request->all());

        return response()->json(['success' => true, 'data' => $sig]);
    }

    public function storeSignatureImage(Request $request)
    {
        $img_path = request()->file('file')->store('uploads', 'public');
        return response()->json(['location' => "/storage/$img_path"]);
    }

    public function deleteSignatureImage(Request $request)
    {
//        dd($request->all());
        foreach ($request->images as $image) {
            $image_path = str_replace("/storage/","../storage/app/public/", $image);

            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }
    }

    public function updateSignature(Request $request)
    {
        Validator::make($request->all(), [
            'content' => 'required',
            'work_mail' => [
                'required',
                Rule::unique('mail_signatures')->ignore($request->id)->where(function ($query) {
                    return $query->where('deleted_at', null);
                })
            ],
            'name' => [
                'max:225',
                'required',
                Rule::unique('mail_signatures')->ignore($request->id)->where(function ($query) {
                    return $query->where('deleted_at', null);
                })
            ]
        ],
        [
            'work_mail.unique' => 'Only one email signature is allowed per work mail/login mail.'
        ])->validate();

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
