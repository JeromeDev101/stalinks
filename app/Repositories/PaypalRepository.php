<?php

namespace App\Repositories;

use App\Repositories\Contracts\PaypalInterface;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PaypalClient;

class PaypalRepository implements PaypalInterface
{
    protected $paypal;

    public function __construct()
    {
        $this->paypal = new PaypalClient();
    }

    public function createOrder($amount)
    {
        $this->paypal->getAccessToken();
        $this->paypal->createOrder(self::buildRequestBody($amount));

        return $this->paypal;
    }

    public function captureOrder($id)
    {
        return $this->paypal->captureAuthorizedPayment($id);
    }

    private static function buildRequestBody($amount)
    {
        return [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                0 => [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $amount
                    ]
                ]
            ]
        ];
    }
}
