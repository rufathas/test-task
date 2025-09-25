<?php

namespace App\Payments\Stripe\Service;

use App\Dao\Entity\PaymentEntity;
use App\Dto\PaymentRequest;
use App\Dto\PurchaseDto;
use App\Enum\PaymentProcessor;
use App\Payments\PaymentService;

class StripePaymentService implements PaymentService
{
    public function supports(PaymentProcessor $paymentProcessor): bool
    {
        // TODO: Implement supports() method.
    }

    public function charge(PaymentRequest $paymentRequest, PaymentEntity $paymentEntity): PurchaseDto
    {
        // TODO: Implement charge() method.
    }
}
