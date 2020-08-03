<?php

namespace App\Http\Controllers;

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

    /**
     * Add new payment type
     *
     * @param Request $request
     * @return void
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
        $payment = PaymentType::findOrFail($request->id);
        $payment->update($input);
        return response()->json(['success' => true], 200);
    }
}
