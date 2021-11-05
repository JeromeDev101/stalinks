<?php

namespace App\Http\Controllers;

use App\PaymentTypeImage;
use Illuminate\Http\Request;
use App\Models\PaymentType;

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

    public function getPaymentTypeImageList()
    {
        $response = PaymentTypeImage::all();

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
        dd($request->all());
        $request->validate(['type' => 'required']);
        $input['type'] = $request->type;
        $input['receive_payment'] = $request->receive_payment;
        $input['send_payment'] = $request->send_payment;
        $payment = PaymentType::findOrFail($request->id);
        $payment->update($input);
        return response()->json(['success' => true], 200);
    }
}
