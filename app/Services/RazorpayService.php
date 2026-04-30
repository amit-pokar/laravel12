<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;

class RazorpayService implements PaymentGatewayInterface
{
    public function pay($amount)
    {
        return "Paid ₹{$amount} via Razorpay";
    }
}