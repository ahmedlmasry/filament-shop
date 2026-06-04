<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use App\Services\Contracts\PaymentGatewayInterface;

class MyFatoorahPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    /**
     * Create a new class instance.
     */
    
    public function __construct()
    {
        $this->base_url = env("MYFATOORAH_BASE_URL");
        $this->api_key = env("MYFATOORAH_API_KEY");
        $this->header = [
            'accept' => 'application/json',
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->api_key,
        ];
    }

    public function sendPayment(Request $request): array
    {
        $response = $this->buildRequest('POST', '/v2/SendPayment', [
            "CustomerName" => $request->name,
            "NotificationOption" => "ALL",
            "InvoiceValue" => $request->amount,
            "CustomerEmail" => $request->email,
            "CallBackUrl" => route('payment.callback'),
            "ErrorUrl" => route('payment.callback'),
        ]);

        if ($response->getData(true)['success']) {
            return ['success' => true, 'url' => $response->getData(true)['data']['Data']['InvoiceURL']];
        }
        return ['success' => false, 'url' => route('payment.failed')];
    }

    public function callBack(Request $request): bool
    {
        $payment_id = $request->input('paymentId');

        $response = $this->buildRequest('POST', '/v2/GetPaymentStatus', [
            'Key' => $payment_id,
            'KeyType' => 'PaymentId'
        ]);

        $response_data = $response->getData(true);

        if ($response_data['success'] && $response_data['data']['Data']['InvoiceStatus'] == 'Paid') {
            return true;
        }

        return false;

    }
}