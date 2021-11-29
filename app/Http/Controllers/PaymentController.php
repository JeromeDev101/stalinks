<?php

namespace App\Http\Controllers;

use App\PaymentTypeImage;
use Illuminate\Http\Request;
use App\Models\PaymentType;
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
            $response = PaymentTypeImage::where('payment_type_id', $request->get('payment_type_id'))->get();
        } else {
            $response = PaymentTypeImage::all();
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
        $request->validate(['type' => 'required']);
        $input['type'] = $request->type;
        PaymentType::create($input);
        $payment = PaymentType::latest()->first();
        return response()->json(['success' => true, 'data' => $payment], 200);
    }

    public function edit(Request $request)
    {
        $request->validate(['type' => 'required']);
        $input['type'] = $request->type;
        $input['receive_payment'] = $request->receive_payment;
        $input['send_payment'] = $request->send_payment;
        $payment = PaymentType::findOrFail($request->id);
        $payment->update($input);
        return response()->json(['success' => true], 200);
    }

    public function deletePaymentTypeImage($id)
    {
        $file = PaymentTypeImage::find($id);
        $paymentTypeId = $file->payment_type_id;

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

        $response = PaymentTypeImage::create([
            'payment_type_id' => $id,
            'path' => $file->getClientOriginalName()
        ]);

        return response()->json($response);
    }
}
