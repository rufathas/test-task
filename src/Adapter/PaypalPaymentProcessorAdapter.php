<?php

namespace App\Adapter;

use App\Dao\ValueObject\Money;
use App\Enum\ExceptionEnum;
use App\Exception\PaymentException;
use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalPaymentProcessorAdapter
{
    /**
     * @throws PaymentException
     */
    public function pay(Money $amount): void
    {
        try {
            (new PaypalPaymentProcessor)->pay($amount->toInteger());
        } catch (Exception $e) {
            throw new PaymentException(ExceptionEnum::PAYPAL_PAYMENT_NOT_COMPLETED);
        }
    }
}
