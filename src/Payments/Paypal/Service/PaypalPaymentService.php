<?php

namespace App\Payments\Paypal\Service;

use App\Dao\Entity\PaymentEntity;
use App\Dto\PaymentRequest;
use App\Enum\PaymentProcessor;
use App\Exception\PaymentException;
use App\Payments\PaymentService;
use App\Payments\Paypal\Enum\StatusEnum;
use App\Payments\Stripe\Adapter\PaypalPaymentProcessorAdapter;

class PaypalPaymentService implements PaymentService
{
    public function supports(PaymentProcessor $paymentProcessor): bool
    {
        return $paymentProcessor === PaymentProcessor::PAYPAL;
    }

    /**
     * @throws PaymentException
     */
    public function charge(PaymentRequest $paymentRequest, PaymentEntity $paymentEntity): void
    {
        $paymentResponse = (new PaypalPaymentProcessorAdapter())->pay($paymentRequest->amount);
        $paymentEntity->setAmount($paymentRequest->amount);
        $paymentEntity->setCurrency($paymentRequest->currency);
        $paymentEntity->setStatus($paymentResponse->status->value);
        $paymentEntity->setProviderRef($paymentResponse->transactionId);

        // Simulate request body
        $paymentEntity->setRequestBody(json_encode(['amount' => $paymentRequest->amount]));
        if ($paymentResponse->status === StatusEnum::FAILED) {
            $paymentEntity->setErrorMessage($paymentResponse->errorMessage);
        }
    }
}
