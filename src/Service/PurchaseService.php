<?php

namespace App\Service;

use App\Enum\PaymentProcessor;

interface PurchaseService
{
    public function purchase(
        int $productId,
        string $taxNumber,
        ?string $couponCode,
        PaymentProcessor $paymentProcessor
    ): void;
}
