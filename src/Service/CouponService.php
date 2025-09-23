<?php

namespace App\Service;

use App\Dao\Entity\CouponEntity;
use App\Dao\ValueObject\Money;

interface CouponService
{
    public function getCouponByCode(string $couponCode): CouponEntity;
    public function amountWithCoupon(Money $amount, string $couponCode): Money;
}
