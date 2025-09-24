<?php

namespace App\Service\Impl;

use App\Dto\CalculatePriceDto;
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

    public function calculatePrice(int $productId, string $taxNumber, ?string $couponCode): CalculatePriceDto
    {
        $productEntity = $this->productService->getById(id: $productId);
        $amountAfterCouponDiscount = $this->couponService->amountWithCoupon(
            amount: $productEntity->getPrice(),
            couponCode: $couponCode
        );

        $taxRateEntity = $this->taxRateService->getTaxRateByTaxNumber(
            taxNumber: $taxNumber
        );

        $finalAmount = $amountAfterCouponDiscount->subtract(
                $amountAfterCouponDiscount->multiply(
                    $taxRateEntity->getRate()->dividedBy(100)->__toString()
                )
            );

        return new CalculatePriceDto(
            productAmount: $productEntity->getPrice(),
            usedCouponAmount: $amountAfterCouponDiscount,
            taxPercent: $taxRateEntity->getRate(),
            finalAmount: $finalAmount
        );
    }
}
