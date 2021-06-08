<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PayPalClient
{
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    public static function environment()
    {
        if (config('paypal.mode') == 'live') {
            $clientId = config('paypal.live.client_id');
            $clientSecret = config('paypal.live.client_secret');

            return new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $clientId = config('paypal.sandbox.client_id');
            $clientSecret = config('paypal.sandbox.client_secret');

            return new SandboxEnvironment($clientId, $clientSecret);
        }
    }
}
