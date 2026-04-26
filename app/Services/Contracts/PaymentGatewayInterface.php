<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    public function sendPayment(Request $request);

    public function callBack(Request $request);
}
