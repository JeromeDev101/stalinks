<?php

namespace App\Http\Controllers;

use App\PaymentTypeImage;
use Illuminate\Http\Request;
use App\Models\PaymentType;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Return list of payment type
     *
     * @return json
     */
    public function getList()
    {
        $list = PaymentType::all();
        return response()->json(['data'=> $list]);
    }

    public function getPaymentTypeImageList(Request $request)
    {
        if ($request->has('payment_type_id')) {
            $response = PaymentTypeImage::where('payment_type_id', $request->get('payment_type_id'))
            ->get();
        } else {
            $response = PaymentTypeImage::whereHas('payment_type', function($q) {
                return $q->where('receive_payment', 'yes');
            })
            ->where('image_type', 'logo')
            ->get();
        }

        return response()->json($response);
    }

    /**
     * Add new payment type
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        if (Gate::denies('create-admin-settings-finance')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $request->validate(['type' => 'required']);
        $input['type'] = $request->type;
        PaymentType::create($input);
        $payment = PaymentType::latest()->first();
        return response()->json(['success' => true, 'data' => $payment], 200);
    }

    public function edit(Request $request)
    {
        if (Gate::denies('update-admin-settings-finance')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $request->validate(['type' => 'required']);
        $input = $request->except(['id', 'show_registration', 'qr_img_path', 'deleted_at', 'created_at', 'updated_at']);
        $payment = PaymentType::findOrFail($request->id);
        $payment->update($input);
        return response()->json(['success' => true], 200);
    }

    public function deletePaymentTypeImage($id)
    {
        $file = PaymentTypeImage::find($id);
        $paymentTypeId = $file->payment_type_id;

        if($file->image_type == 'qr') {
            $paymet_type = PaymentType::find($paymentTypeId);
            $paymet_type->update(['qr_img_path'=>null]);
        }

        Storage::delete($file->path);

        PaymentTypeImage::destroy($file->id);

        return response()->json($paymentTypeId);
    }

    public function uploadPaymentTypeImage($id, Request $request)
    {
        $file = $request->file('file');

        if ($file) {
            Storage::disk('local')->put($file->getClientOriginalName(), file_get_contents($file));
        }

        if($request->image_type === 'qr') {
            $paymet_type = PaymentType::find($id);
            $paymet_type->update(['qr_img_path'=>$file->getClientOriginalName()]);
        }

        $response = PaymentTypeImage::create([
            'image_type' => $request->image_type,
            'payment_type_id' => $id,
            'path' => $file->getClientOriginalName()
        ]);

        return response()->json($response);
    }
}
