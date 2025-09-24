<?php

namespace App\Adapter;

use App\Dao\ValueObject\Money;
use App\Enum\ExceptionEnum;
use App\Exception\PaymentException;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentProcessorAdapter
{
    /**
     * @throws PaymentException
     */
    public function pay(Money $amount): void
    {
        $response = (new StripePaymentProcessor())->processPayment($amount->toFloat());
        if (!$response)
            throw new PaymentException(ExceptionEnum::STRIPE_PAYMENT_NOT_COMPLETED);

    }
}
