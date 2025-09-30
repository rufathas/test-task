<?php

namespace App\Tests\Unit\Service;

use App\Dao\Entity\PaymentEntity;
use App\Dao\Entity\PurchaseEntity;
use App\Dao\ValueObject\Money;
use App\Dto\CalculatePriceDto;
use App\Dto\PaymentRequest;
use App\Dto\PurchaseRequestDto;
use App\Enum\PaymentProcessor;
use App\Payments\PaymentFactory;
use App\Payments\PaymentService;
use App\Service\Impl\PurchaseServiceImpl;
use App\Service\PriceCalculatorService;
use App\Service\TaxRateService;
use Brick\Math\BigDecimal;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class PurchaseServiceImplTest extends TestCase
{
    private PriceCalculatorService $priceCalculatorService;
    private TaxRateService $taxRateService;
    private EntityManagerInterface $entityManager;
    private PurchaseServiceImpl $service;
    private PaymentService $paymentService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->priceCalculatorService = $this->createMock(PriceCalculatorService::class);
        $this->taxRateService = $this->createMock(TaxRateService::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->paymentService = $this->createMock(PaymentService::class);

        $paymentFactory = $this->createConfiguredMock(PaymentFactory::class, [
            'getPaymentService' => $this->paymentService,
        ]);

        $this->service = new PurchaseServiceImpl(
            priceCalculatorService: $this->priceCalculatorService,
            taxRateService: $this->taxRateService,
            paymentFactory: $paymentFactory,
            entityManager: $this->entityManager
        );
    }

    public function testPurchase_Success(): void
    {
        $requestDto = new PurchaseRequestDto(
            product: 1,
            taxNumber: 'DE123456789',
            couponCode: 'SAVE15',
            paymentProcessor: PaymentProcessor::PAYPAL
        );

        $calculatePriceDto = new CalculatePriceDto(
            productAmount: Money::fromString('100.00'),
            amountAfterCouponDiscount: Money::fromString('85.00'),
            taxPercent: BigDecimal::of('15.00'),
            finalAmount: Money::fromString('72.25')
        );

        $this->priceCalculatorService
            ->expects($this->once())
            ->method('calculatePrice')
            ->with($requestDto->product, $requestDto->taxNumber, $requestDto->couponCode)
            ->willReturn($calculatePriceDto);

        $this->taxRateService
            ->expects($this->once())
            ->method('getCountryCodeByTaxNumber')
            ->with($requestDto->taxNumber)
            ->willReturn('DE');

        $this->entityManager
            ->expects($this->exactly(2))
            ->method('persist');

        $this->paymentService
            ->expects($this->once())
            ->method('charge')
            ->with($this->isInstanceOf(PaymentRequest::class));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->service->purchase($requestDto);
    }
}
