<?php

namespace App\Repositories;

use App\Repositories\Contracts\PaypalInterface;
use App\Services\InvoiceService;
use App\Services\PayPalClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PaypalPayoutsSDK\Payouts\PayoutsGetRequest;
use PaypalPayoutsSDK\Payouts\PayoutsPostRequest;

class PaypalRepository implements PaypalInterface
{
    protected $paypal;

    protected $invoiceService;

    public function __construct()
    {
        $this->paypal = PayPalClient::client();
        $this->invoiceService = new InvoiceService();
    }

    public function createOrder($request)
    {
        $payload = new OrdersCreateRequest();
        $payload->prefer('return=representation');
        $payload->body = self::buildOrderRequestBody($request);

        return $this->paypal->execute($payload);
    }

    public function createPayout($request)
    {
        $payload = new PayoutsPostRequest();
        $payload->body = self::buildPayoutRequestBody($request);

        return $this->paypal->execute($payload);
    }

    public function captureOrder($id)
    {
        $payload = new OrdersCaptureRequest($id);

        $result = $this->paypal->execute($payload);

        return $result;
    }

    public function fetchPayout($id)
    {
        $payload = new PayoutsGetRequest($id);

        return $this->paypal->execute($payload);
    }

    private static function buildOrderRequestBody($request)
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
            $requestBody['purchase_units'][0]['payee'] = [
                'email_address' => $request['email']
            ];
        }

        return $requestBody;
    }

    private static function buildPayoutRequestBody($request)
    {
        $requestBody = json_decode(
            '{
                "sender_batch_header":
                {
                  "email_subject": "StaLinks Payout"
                },
                "items": [
                {
                  "recipient_type": "EMAIL",
                  "receiver": "'. $request['email'] .'",
                  "sender_item_id": '. rand(1, 10).',
                  "amount":
                  {
                    "currency": "USD",
                    "value": "'. $request['amount'] .'"
                  }
                }]
              }',
            true);

        return $requestBody;
    }
}
