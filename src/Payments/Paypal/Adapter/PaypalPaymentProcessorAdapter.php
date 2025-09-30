<?php

namespace App\Payments\Paypal\Adapter;

use App\Dao\ValueObject\Money;
use App\Payments\Paypal\Dto\PaypalPaymentResponse;
use App\Payments\Paypal\Enum\StatusEnum;
use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalPaymentProcessorAdapter
{
    public function pay(Money $amount): PaypalPaymentResponse
    {
        try {
            (new PaypalPaymentProcessor)->pay($amount->toInteger());

            //Response simulation
            return new PaypalPaymentResponse(
                StatusEnum::COMPLETED, 'PAYPAL_TRANS_12345');
        } catch (Exception $e) {
            //Response simulation
            return new PaypalPaymentResponse(StatusEnum::FAILED, 'PAYPAL_TRANS_12345', 'Some error');
        }
    }
}
