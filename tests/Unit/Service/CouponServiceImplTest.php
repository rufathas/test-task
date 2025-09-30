<?php

namespace App\Tests\Unit\Service;

use App\Dao\Entity\CouponEntity;
use App\Dao\ValueObject\Money;
use App\Enum\ExceptionEnum;
use App\Exception\NotFoundException;
use App\Repository\CouponRepository;
use App\Service\Impl\CouponServiceImpl;
use Brick\Math\BigDecimal;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class CouponServiceImplTest extends TestCase
{
    private CouponRepository $repo;
    private CouponServiceImpl $service;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->repo = $this->createMock(CouponRepository::class);
        $this->service = new CouponServiceImpl($this->repo);
    }

    /**
     * @throws Exception
     * @throws NotFoundException
     */
    public function testGetCouponByCode_ReturnsEntity(): void
    {
        $code = 'SAVE10';
        $coupon = $this->createConfiguredMock(CouponEntity::class, [
            'getCode' => $code,
            'isActive' => true,
            'getValidFrom' => new DateTimeImmutable('-1 day'),
            'getValidTo' => new DateTimeImmutable('+1 day'),
            'isPercent' => true,
            'getPercentFraction' => '0.10',
        ] );


        $this->repo
            ->expects($this->once())
            ->method('findOneByCodeAndByIsActiveAndBetweenValidDatesOrFail')
            ->willReturn($coupon);

        $result = $this->service->getCouponByCode($code);

        $this->assertSame($coupon->getCode(), $result->getCode());
        $this->assertSame($coupon->isActive(), $result->isActive());
        $this->assertSame($coupon->getValidFrom(), $result->getValidFrom());
        $this->assertSame($coupon->getValidTo(), $result->getValidTo());
        $this->assertSame($coupon->isPercent(), $result->isPercent());
        $this->assertSame($coupon->getPercentFraction(), $result->getPercentFraction());
    }

    /**
     * @throws NotFoundException
     */
    public function testGetCouponByCode_ThrowsNotFound(): void
    {
        $this->repo
            ->expects($this->once())
            ->method('findOneByCodeAndByIsActiveAndBetweenValidDatesOrFail')
            ->willThrowException(new NotFoundException(ExceptionEnum::COUPON_CODE_NOT_FOUND));

        $this->expectExceptionObject(new NotFoundException(ExceptionEnum::COUPON_CODE_NOT_FOUND));

        $this->service->getCouponByCode('NOPE');
    }

    /**
     * @throws Exception
     * @throws NotFoundException
     */
    public function testAmountWithCoupon_Percent(): void
    {
        $amount = Money::fromString('140.00');
        $finalAmount =  Money::fromString('126.00');

        $coupon = $this->createConfiguredMock(CouponEntity::class, [
            'getCode' => 'SAVE10',
            'isPercent' => true,
            'getValidFrom' => new DateTimeImmutable('-1 day'),
            'getValidTo' => new DateTimeImmutable('+1 day'),
            'getValue' => BigDecimal::of('10.00'),
            'getPercentFraction' => '0.10',
        ]);

        $this->repo
            ->method('findOneByCodeAndByIsActiveAndBetweenValidDatesOrFail')
            ->willReturn($coupon);

        $result = $this->service->amountWithCoupon($amount, 'SAVE10');

        $this->assertSame($finalAmount->toString(), $result->toString());
    }

    /**
     * @throws Exception
     * @throws NotFoundException
     */
    public function testAmountWithCoupon_Fixed(): void
    {
        $amount = Money::fromString('140.00');
        $finalAmount =  Money::fromString('130.00');

        $coupon = $this->createConfiguredMock(CouponEntity::class, [
            'getCode' => 'SAVE10',
            'isPercent' => false,
            'getValidFrom' => new DateTimeImmutable('-1 day'),
            'getValidTo' => new DateTimeImmutable('+1 day'),
            'getValue' => BigDecimal::of('10.00')
        ]);

        $this->repo
            ->method('findOneByCodeAndByIsActiveAndBetweenValidDatesOrFail')
            ->willReturn($coupon);

        $result = $this->service->amountWithCoupon($amount, 'SAVE10');

        $this->assertSame($finalAmount->toString(), $result->toString());
    }
}
