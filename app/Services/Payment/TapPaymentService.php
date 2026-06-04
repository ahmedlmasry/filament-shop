<?php

namespace App\Services\Payment;

use App\Services\Contracts\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Log;

class TapPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    /**
     * Create a new class instance.
     */

    public function __construct()
    {
        $this->base_url = env("TAP_BASE_URL");
        $this->api_key = env("TAP_API_KEY");
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->api_key,
        ];
    }

     public function sendPayment(Request $request)
    {
        //validate data before sending it
        $data = $request->all();
        $data['source'] = ['id' => 'src_all'];
        $data['redirect'] = ['url' => $request->getSchemeAndHttpHost() . '/api/v1/payment/callback'];

        $response=$this->buildRequest('POST', '/v2/charges/', $data);
        //handel payment response data and return it
        if($response->getData(true)['success']){

            return['success'=>true,'url'=>$response->getData(true)['data']['transaction']['url']];
        }
        return['success'=>false,'url'=>route('payment.failed')];

    }
    public function callBack(Request $request): bool
    {
        $chargeId = $request->input('tap_id');

        $response = $this->buildRequest('GET', "/v2/charges/$chargeId");
        $response_data = $response->getData(true);

        Log::info('tap_response',[
            'callback_response' => $request->all(),
            'response' => $response_data
        ]);

        if($response_data['success'] && $response_data['data']['status'] == 'CAPTURED') {
            //save order data and return true
            return true;
        }
        return false;
    }
}
