<?php

namespace App\Service\Impl;

use App\Dao\Entity\CouponEntity;
use App\Dao\ValueObject\Money;
use App\Exception\NotFoundException;
use App\Repository\CouponRepository;
use App\Service\CouponService;
use Brick\Math\BigDecimal;
use DateTimeImmutable;

class CouponServiceImpl implements CouponService
{
    public function __construct(
        private readonly CouponRepository $couponRepository,
    )
    {
    }

    /**
     * @throws NotFoundException
     */
    public function getCouponByCode(string $couponCode): CouponEntity
    {
        return $this->couponRepository
            ->findOneByCodeAndByIsActiveAndBetweenValidDatesOrFail(
                code: $couponCode,
                date: new DateTimeImmutable(),
                isActive: true
            );
    }

    /**
     * @throws NotFoundException
     */
    public function amountWithCoupon(Money $amount, string $couponCode): Money
    {
        $couponEntity = $this->getCouponByCode(couponCode: $couponCode);
        $couponValue = $couponEntity->getValue();
        if ($couponEntity->isPercent()) {
            return $amount->subtract($amount->multiply($couponEntity->getPercentFraction()));
        }

        return $amount->subtract(new Money($couponValue));
    }
}
