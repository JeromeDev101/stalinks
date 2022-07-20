<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseCategoryRequest;
use App\Models\PurchaseCategory;
use Illuminate\Http\Request;

class PurchaseCategoryController extends Controller
{
    public function index (Request $request) {
        $categories = PurchaseCategory::select('*')->with('types');

        if( isset($request->paginate) && $request->paginate == 'All' ){
            $categories = $categories->get();

            return response()->json([
                'data' => $categories,
                'total' => $categories->count(),
            ],200);
        }else{
            $categories = $categories->paginate($request->paginate);

            return response()->json($categories);
        }
    }

    public function selection () {
        $categories = PurchaseCategory::select('id', 'name')->get();

        return response()->json(['data'=> $categories], 200);
    }

    public function store (PurchaseCategoryRequest $request) {
        $category = new PurchaseCategory();

        $category->fill($request->all());

        $category->save();

        return response()->json(['success' => true], 200);
    }

    public function update (PurchaseCategoryRequest $request) {
        $inputs = $request->only('name', 'description');

        $category = PurchaseCategory::find($request->id);

        if (!$category) {
            return response()->json(['success' => false], '422');
        }else{
            $category->update($inputs);
            return response()->json(['success' => true], '200');
        }
    }
}
