<?php

namespace App\Payments;

use App\Dto\PaymentRequest;
use App\Enum\PaymentProcessor;

interface PaymentService
{
    public function supports(PaymentProcessor $paymentProcessor): bool;

    public function charge(PaymentRequest $paymentRequest): void;
}
