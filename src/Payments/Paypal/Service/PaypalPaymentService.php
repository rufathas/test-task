<?php

namespace App\Payments\Paypal\Service;

use App\Dto\PaymentRequest;
use App\Enum\PaymentProcessor;
use App\Payments\PaymentService;
use App\Payments\Paypal\Adapter\PaypalPaymentProcessorAdapter;
use App\Payments\Paypal\Enum\StatusEnum;

class PaypalPaymentService implements PaymentService
{
    public function __construct(
        private readonly PaypalPaymentProcessorAdapter $adapter
    )
    {}

    public function supports(PaymentProcessor $paymentProcessor): bool
    {
        return $paymentProcessor === PaymentProcessor::PAYPAL;
    }

    public function charge(PaymentRequest $paymentRequest): void
    {
        $purchaseEntity = $paymentRequest->purchaseEntity;
        $paymentEntity = $paymentRequest->paymentEntity;

        $paymentResponse = $this->adapter->pay($purchaseEntity->getTotalAmount());
        $paymentEntity->setAmount($purchaseEntity->getTotalAmount());
        $paymentEntity->setCurrency($purchaseEntity->getCurrency());
        $paymentEntity->setStatus($paymentResponse->status->value);
        $paymentEntity->setProviderRef($paymentResponse->transactionId);

        // Simulate request body
        $paymentEntity->setRequestBody(json_encode(['amount' => $purchaseEntity->getTotalAmount()->toString()]));
        if ($paymentResponse->status === StatusEnum::FAILED) {
            $paymentEntity->setErrorMessage($paymentResponse->errorMessage);
        }
    }
}
