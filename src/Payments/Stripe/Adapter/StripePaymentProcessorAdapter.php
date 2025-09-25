<?php

namespace App\Payments\Stripe\Adapter;

use App\Dao\ValueObject\Money;
use App\Payments\Stripe\Dto\StripePaymentResponse;
use App\Payments\Stripe\Enum\StatusEnum;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentProcessorAdapter
{
    public function pay(Money $amount): StripePaymentResponse
    {
        $response = (new StripePaymentProcessor())->processPayment($amount->toFloat());
        if ($response) {
            //Response simulation
            return new StripePaymentResponse(
                StatusEnum::CREATED, 'STRIPE_TRANS_12345');
        }

        //Response simulation
        return new StripePaymentResponse(
            StatusEnum::ERROR, 'STRIPE_TRANS_12345','Some Error message');

    }
}
