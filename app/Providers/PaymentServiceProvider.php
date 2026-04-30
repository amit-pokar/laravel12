<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayInterface;
use App\Services\StripeService;
use App\Services\RazorpayService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            PaymentGatewayInterface::class,
            function () {
                if (env('GATEWAY', '') == 'stripe') {
                    return new StripeService();
                }
            
                return new RazorpayService();
            }
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
