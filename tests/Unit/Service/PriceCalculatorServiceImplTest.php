<?php

namespace App\Tests\Unit\Service;

use App\Dao\Entity\ProductEntity;
use App\Dao\Entity\TaxRateEntity;
use App\Dao\ValueObject\Money;
use App\Service\CouponService;
use App\Service\Impl\PriceCalculatorServiceImpl;
use App\Service\ProductService;
use App\Service\TaxRateService;
use Brick\Math\BigDecimal;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class PriceCalculatorServiceImplTest extends TestCase
{
    private ProductService $productService;
    private CouponService $couponService;
    private TaxRateService $taxRateService;

    private PriceCalculatorServiceImpl $service;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->productService = $this->createMock(ProductService::class);
        $this->couponService = $this->createMock(CouponService::class);
        $this->taxRateService = $this->createMock(TaxRateService::class);

        $this->service = new PriceCalculatorServiceImpl(
            productService: $this->productService,
            couponService: $this->couponService,
            taxRateService: $this->taxRateService
        );
    }

    /**
     * @throws Exception
     */
    public function testCalculatePrice_WithCoupon()
    {
        $productId = 12;
        $taxNumber = 'DE123456789';
        $couponCode = 'SAVE15';

        $productEntity = $this->createConfiguredMock(ProductEntity::class, [
            'getId' => $productId,
            'getPrice' => Money::fromString('100.00')
        ]);

        $amountWithCoupon = Money::fromString('85.00');

        $taxRateEntity = $this->createConfiguredMock(TaxRateEntity::class, [
            'getRate' => BigDecimal::of('15.00')
        ]);

        $this->productService
            ->expects($this->once())
            ->method('getById')->willReturn($productEntity);
        $this->couponService
            ->expects($this->once())
            ->method('amountWithCoupon')->willReturn($amountWithCoupon);
        $this->taxRateService
            ->expects($this->once())
            ->method('getTaxRateByTaxNumber')->willReturn($taxRateEntity);

        $result = $this->service->calculatePrice($productId, $taxNumber, $couponCode);

        $this->assertSame($result->productAmount->toString(), $productEntity->getPrice()->toString());
        $this->assertSame($result->amountAfterCouponDiscount->toString(), $amountWithCoupon->toString());
        $this->assertSame($result->taxPercent->__toString(), $taxRateEntity->getRate()->__toString());
        $this->assertSame($result->finalAmount->toString(),'72.25');
    }

    /**
     * @throws Exception
     */
    public function testCalculatePrice_WithOutCoupon()
    {
        $productId = 12;
        $taxNumber = 'DE123456789';
        $couponCode = null;

        $productEntity = $this->createConfiguredMock(ProductEntity::class, [
            'getId' => $productId,
            'getPrice' => Money::fromString('100.00')
        ]);


        $taxRateEntity = $this->createConfiguredMock(TaxRateEntity::class, [
            'getRate' => BigDecimal::of('15.00')
        ]);

        $this->productService
            ->expects($this->once())
            ->method('getById')->willReturn($productEntity);
        $this->taxRateService
            ->expects($this->once())
            ->method('getTaxRateByTaxNumber')->willReturn($taxRateEntity);

        $result = $this->service->calculatePrice($productId, $taxNumber, $couponCode);

        $this->assertSame($result->productAmount->toString(), $productEntity->getPrice()->toString());
        $this->assertSame($result->amountAfterCouponDiscount->toString(), $productEntity->getPrice()->toString());
        $this->assertSame($result->taxPercent->__toString(), $taxRateEntity->getRate()->__toString());
        $this->assertSame($result->finalAmount->toString(),'85.00');
    }
}
