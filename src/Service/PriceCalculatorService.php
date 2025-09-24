<?php

namespace App\Service;

use App\Dto\CalculatePriceDto;

interface PriceCalculatorService
{
    public function calculatePrice(
        int $productId,
        string $taxNumber,
        ?string $couponCode
    ): CalculatePriceDto;
}
