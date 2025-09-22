<?php

namespace App\Service\Impl;

use App\Dao\Entity\CouponEntity;
use App\Exception\NotFoundException;
use App\Repository\CouponRepository;
use App\Service\CouponService;

class CouponServiceImpl implements CouponService
{
    public function __construct(
        private readonly CouponRepository $couponRepository,
    )
    {}

    /**
     * @throws NotFoundException
     */
    public function getCouponByCode(string $code): CouponEntity
    {
        return $this->couponRepository->findOneByCodeOrFail(code: $code);
    }
}
