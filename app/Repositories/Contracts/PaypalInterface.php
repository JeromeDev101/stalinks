<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface PaypalInterface
{
    public function createOrder($amount);

    public function captureOrder($id);
}
