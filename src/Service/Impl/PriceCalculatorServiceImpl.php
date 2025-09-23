<?php

namespace App\Service\Impl;

use App\Service\CouponService;
use App\Service\PriceCalculatorService;
use App\Service\ProductService;
use App\Service\TaxRateService;

class PriceCalculatorServiceImpl implements PriceCalculatorService
{

    public function __construct(
        private readonly ProductService $productService,
        private readonly CouponService $couponService,
        private readonly TaxRateService $taxRateService,
    )
    {}

    public function calculatePrice(int $productId, string $taxNumber, ?string $couponCode): void
    {
        $productEntity = $this->productService->getById(id: $productId);
        $taxRateEntity = $this->taxRateService->getTaxRateByTaxNumber(taxNumber: $taxNumber);
        $amountAfterCouponDiscount = $this->couponService->amountWithCoupon(
            amount: $productEntity->getPrice(),
            couponCode: $couponCode
        );
    }
}
