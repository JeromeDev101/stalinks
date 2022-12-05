<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseTypeRequest;
use App\Models\PurchaseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PurchaseTypeController extends Controller
{
    public function selection () {
        $types = PurchaseType::select('id', 'name', 'purchase_category_id')->get();

        return response()->json(['data'=> $types], 200);
    }

    public function store (PurchaseTypeRequest $request) {
        if (Gate::denies('create-purchases-config')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $type = new PurchaseType();

        $type->fill($request->only('name', 'description', 'purchase_category_id'));

        $type->save();

        return response()->json(['success' => true], 200);
    }

    public function update (PurchaseTypeRequest $request) {
        if (Gate::denies('update-purchases-config')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $inputs = $request->only('name', 'description');

        $type = PurchaseType::find($request->id);

        if (!$type) {
            return response()->json(['success' => false], '422');
        }else{
            $type->update($inputs);
            return response()->json(['success' => true], '200');
        }
    }
}
