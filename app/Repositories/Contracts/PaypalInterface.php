<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface PaypalInterface
{
    public function createOrder($request);

    public function createPayout($request);

    public function captureOrder($id);

    public function fetchPayout($id);
}
