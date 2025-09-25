<?php

namespace App\Service\Impl;

use App\Dto\PurchaseRequestDto;
use App\Service\PriceCalculatorService;
use App\Service\PurchaseService;

class PurchaseServiceImpl implements PurchaseService
{
    public function __construct(
        private readonly PriceCalculatorService $priceCalculatorService,
    )
    {
    }

    public function purchase(PurchaseRequestDto $requestDto): void
    {
       $calculatePriceDto = $this->priceCalculatorService->calculatePrice(
            productId: $requestDto->product,
            taxNumber: $requestDto->taxNumber,
            couponCode: $requestDto->couponCode
        );

    }

}
