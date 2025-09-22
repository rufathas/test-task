<?php

namespace App\Service;

use App\Dao\Entity\CouponEntity;

interface CouponService
{
    public function getCouponByCode(string $code): CouponEntity;
}
