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

    public function createOrder($request)
    {
        $this->paypal->getAccessToken();
        $this->paypal->createOrder(self::buildRequestBody($request));

        return $this->paypal;
    }

    public function captureOrder($id)
    {
        return $this->paypal->captureAuthorizedPayment($id);
    }

    private static function buildRequestBody($request)
    {
        $requestBody = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                0 => [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $request['amount']
                    ],
                ]
            ]
        ];

        if (isset($request['email'])) {
            $requestBody['purchase_units']['payee'] = [
                'email_address' => $request['email']
            ];
        }

        return $requestBody;
    }
}
