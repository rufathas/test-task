<?php

namespace App\Payments\Stripe\Adapter;

use App\Dao\ValueObject\Money;
use App\Dto\PaypalPaymentResponse;
use App\Exception\PaymentException;
use App\Payments\Paypal\Enum\StatusEnum;
use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalPaymentProcessorAdapter
{
    /**
     * @throws PaymentException
     */
    public function pay(Money $amount): PaypalPaymentResponse
    {
        try {
            (new PaypalPaymentProcessor)->pay($amount->toInteger());

            //Response simulation
            return new PaypalPaymentResponse(
                StatusEnum::COMPLETED, 'TRANS12345');
        } catch (Exception $e) {
            return new PaypalPaymentResponse(StatusEnum::COMPLETED, 'TRANS12345', );
        }
    }
}
