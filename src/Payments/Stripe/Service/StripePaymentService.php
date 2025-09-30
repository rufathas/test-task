<?php

namespace App\Payments\Stripe\Service;

use App\Dto\PaymentRequest;
use App\Enum\PaymentProcessor;
use App\Payments\PaymentService;
use App\Payments\Stripe\Adapter\StripePaymentProcessorAdapter;
use App\Payments\Stripe\Enum\StatusEnum;

class StripePaymentService implements PaymentService
{
    public function __construct(
        private readonly StripePaymentProcessorAdapter $adapter
    )
    {}

    public function supports(PaymentProcessor $paymentProcessor): bool
    {
        return $paymentProcessor === PaymentProcessor::STRIPE;
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

        $paymentEntity->setRequestBody(
            json_encode([
                'amount' => $purchaseEntity->getTotalAmount()->toString(),
                'currency' => $purchaseEntity->getCurrency()
            ])
        );

        if ($paymentResponse->status === StatusEnum::ERROR) {
            $paymentEntity->setErrorMessage($paymentResponse->errorMessage);
        }
    }
}
