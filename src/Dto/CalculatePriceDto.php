<?php

namespace App\Dto;

use App\Dao\ValueObject\Money;
use Brick\Math\BigDecimal;

class CalculatePriceDto
{
    public function __construct(
        public Money      $productAmount,
        public Money      $amountAfterCouponDiscount,
        public BigDecimal $taxPercent,
        public Money      $finalAmount,
    )
    {
    }
}
