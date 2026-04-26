<?php

namespace App\Providers;

use App\Services\Contracts\PaymentGatewayInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\TapPaymentService;
use App\Services\PaymobPaymentService;

class paymentServiceprovider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //if you have multi payment gateways and want to use one of them you shoud send the pramater with data
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            $gateway = request()->input('gateway');

            return match ($gateway) {
                'paymob' => $app->make(PaymobPaymentService::class),
                'tap' => $app->make(TapPaymentService::class),
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
