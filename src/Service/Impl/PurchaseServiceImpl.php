<?php

namespace App\Service\Impl;

use App\Dao\Entity\PaymentEntity;
use App\Dao\Entity\PurchaseEntity;
use App\Dto\PaymentRequest;
use App\Dto\PurchaseRequestDto;
use App\Enum\Currency;
use App\Enum\PurchaseStatus;
use App\Payments\PaymentFactory;
use App\Service\PriceCalculatorService;
use App\Service\PurchaseService;
use App\Service\TaxRateService;
use Doctrine\ORM\EntityManagerInterface;

readonly class PurchaseServiceImpl implements PurchaseService
{
    public function __construct(
        private PriceCalculatorService $priceCalculatorService,
        private TaxRateService         $taxRateService,
        private PaymentFactory         $paymentFactory,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function purchase(PurchaseRequestDto $requestDto): void
    {
        $calculatePriceDto = $this->priceCalculatorService->calculatePrice(
            productId: $requestDto->product,
            taxNumber: $requestDto->taxNumber,
            couponCode: $requestDto->couponCode
        );

        $purchaseEntity = new PurchaseEntity(
            taxNumber: $requestDto->taxNumber,
            country: $this->taxRateService->getCountryCodeByTaxNumber($requestDto->taxNumber),
            totalNetAmount: $calculatePriceDto->productAmount,
            subtotalNet: $calculatePriceDto->amountAfterCouponDiscount,
            taxAmount: $calculatePriceDto->finalAmount,
            totalAmount: $calculatePriceDto->finalAmount,
            currency: Currency::EUR, //mock currency
            status: PurchaseStatus::PENDING,
            couponCode: $requestDto->couponCode
        );

        $paymentEntity = new PaymentEntity(
            purchase: $purchaseEntity,
            provider: $requestDto->paymentProcessor->value,
        );

        $this->entityManager->persist($purchaseEntity);
        $this->entityManager->persist($paymentEntity);

        $this->paymentFactory->getPaymentService($requestDto->paymentProcessor)->charge(
            paymentRequest: new PaymentRequest(
                purchaseEntity: $purchaseEntity,
                paymentEntity: $paymentEntity
            ),
        );

        $this->entityManager->flush();
    }

}
