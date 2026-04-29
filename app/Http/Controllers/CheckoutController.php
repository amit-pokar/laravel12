<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGatewayInterface;

class CheckoutController extends Controller
{
    protected $paymentGateway;

    public function __construct(PaymentGatewayInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function checkout()
    {
        $amount = 1000; // Example amount
        return $this->paymentGateway->pay($amount);
    }
}
