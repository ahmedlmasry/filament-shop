<?php

namespace App\Services;

use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Services\Contracts\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Log;
use Storage;

class PaymobPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    protected $api_key;
    protected array $integrations_ids;
    protected PaymentRepositoryInterface $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->base_url = env("PAYMOB_BASE_URL");
        $this->api_key = env("PAYMOB_API_KEY");
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $this->integrations_ids = [5586188, 5586582];
    }

    //first generate token to access api
    protected function generateToken()
    {
        $response = $this->buildRequest('POST', '/api/auth/tokens', ['api_key' => $this->api_key]);
        $data = $response->getData(true);

        if (!$data['success'] || empty($data['data']['token'])) {
            throw new \Exception('Paymob authentication failed: ' . ($data['message'] ?? 'No token returned'));
        }

        return $data['data']['token'];
    }

    public function sendPayment(Request $request): array
    {
        $token = $this->generateToken();

        // Payment-links endpoint uses multipart form data, not JSON.
        // Remove Content-Type so Guzzle sets it correctly for form_params.
        $this->header = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        // Guzzle sends repeated form fields for multiple values: payment_methods=111&payment_methods=222
        $paymentMethods = array_map('strval', $this->integrations_ids);

        $data = [
            'amount_cents' => $request->input('amount_cents'),
            'currency' => $request->input('currency', 'EGP'),
            'payment_methods' => $paymentMethods,
            'is_live' => $request->input('is_live', false),
            'full_name' => $request->input('full_name', ''),
            'email' => $request->input('email', ''),
            'phone_number' => $request->input('phone_number', ''),
            'description' => $request->input('description', ''),
            'expires_at' => $request->input('expires_at', now()->addDays(7)->format('Y-m-d\TH:i:s')),
            'reference_id' => $request->input('reference_id', uniqid('REF')),
            'redirection_url' => 'http://filament-shop.test/payment/success', // Crucial: tells Paymob where to redirect
        ];

        $response = $this->buildRequest('POST', '/api/ecommerce/payment-links', $data, 'form_params');
        //handel payment response data and return it
        if ($response->getData(true)['success']) {


            return ['success' => true, 'url' => $response->getData(true)['data']['url']];
        }

        return ['success' => false, 'url' => route('payment.failed')];
    }

    public function callBack(Request $request): bool
    {
        dd($request->all());
        $response = $request->all();
        Storage::put('paymob_response.json', json_encode($request->all()));
        if (isset($response['success']) && $response['success'] === 'true') {

            return true;
        }
        return false;

    }
}
