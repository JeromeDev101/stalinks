<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Purchases;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchasesController extends Controller
{
    public function index (Request $request, PurchaseService $purchaseService) {
        $purchases = $purchaseService->index($request);

        return response()->json($purchases, 200);
    }

    public function selection ($mode, PurchaseService $purchaseService) {
        $buyers = $purchaseService->selection($mode);

        return response()->json(['data'=> $buyers], 200);
    }

    public function store (PurchaseRequest $request, PurchaseService $purchaseService) {
        $purchaseService->store($request, $request->mode);

        return response()->json(['success' => true], 200);
    }

    public function update (PurchaseRequest $request, PurchaseService $purchaseService) {
        return $purchaseService->update($request);
    }

    public function delete (Purchases $purchase) {
        $purchase->delete();

        return response()->json(['success' => true], 200);
    }

    public function getCategoryReportData (Request $request, PurchaseService $purchaseService) {
        $data = $purchaseService->getCategoryReportData($request);

        return response()->json($data, 200);
    }

    public function getPurchaseTypeReportData (Request $request, PurchaseService $purchaseService) {
        $data = $purchaseService->getPurchaseTypeReportData($request);

        return response()->json($data, 200);
    }

    public function getPaymentMethodReportData (Request $request, PurchaseService $purchaseService) {
        $data = $purchaseService->getPaymentMethodReportData($request);

        return response()->json($data, 200);
    }

    public function getPurchaseModules (PurchaseService $purchaseService) {
        $modules = $purchaseService->getModules();

        return response()->json(['data'=> $modules], 200);
    }
}
