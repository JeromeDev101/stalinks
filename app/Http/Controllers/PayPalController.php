<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\PaypalInterface;
use Illuminate\Http\Request;

class PayPalController extends Controller
{
    protected $paypalRepository;

    public function __construct(PaypalInterface $paypalRepository)
    {
        $this->paypalRepository = $paypalRepository;
    }

    public function createOrder(Request $request)
    {
        return $this->paypalRepository->createOrder($request->all());
    }

    public function captureOrder($id)
    {
        return $this->paypalRepository->captureOrder($id);
    }
}
