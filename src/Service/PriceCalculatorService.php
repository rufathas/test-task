<?php

namespace App\Service;

interface PriceCalculatorService
{
    public function calculatePrice(int $productId, string $taxNumber, ?string $couponCode): void;
}
