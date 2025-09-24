<?php

namespace App\Service\Impl;

use App\Enum\PaymentProcessor;
use App\Service\PurchaseService;
use App\Service\PriceCalculatorService;

class PurchaseServiceImpl implements PurchaseService
{
    public function __construct(
        private readonly PriceCalculatorService $priceCalculatorService,
    )
    {
    }

    public function purchase(
        int $productId,
        string $taxNumber,
        ?string $couponCode,
        PaymentProcessor $paymentProcessor
    ): void
    {
       $calculatePriceDto = $this->priceCalculatorService->calculatePrice(
            productId: $productId,
            taxNumber: $taxNumber,
            couponCode: $couponCode
        );


    }

}
