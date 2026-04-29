<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;

class StripeService implements PaymentGatewayInterface
{
    public function pay($amount)
    {
        return "Paid ₹{$amount} via Stripe";
    }
}