<?php

namespace App\Tests\Unit\Payments\Stripe\Service;

use App\Dao\Entity\PaymentEntity;
use App\Dao\Entity\PurchaseEntity;
use App\Dao\ValueObject\Money;
use App\Dto\PaymentRequest;
use App\Enum\Currency;
use App\Enum\PaymentProcessor;
use App\Payments\Stripe\Adapter\StripePaymentProcessorAdapter;
use App\Payments\Stripe\Dto\StripePaymentResponse;
use App\Payments\Stripe\Enum\StatusEnum;
use App\Payments\Stripe\Service\StripePaymentService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class StripePaymentServiceTest extends TestCase
{
    private StripePaymentProcessorAdapter $adapter;
    private StripePaymentService $service;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->adapter = $this->createMock(StripePaymentProcessorAdapter::class);
        $this->service = new StripePaymentService($this->adapter);
    }

    public function testSupports_True()
    {
        $result = $this->service->supports(PaymentProcessor::STRIPE);
        $this->assertTrue($result);
    }

    public function testSupports_False()
    {
        $result = $this->service->supports(PaymentProcessor::PAYPAL);
        $this->assertFalse($result);
    }

    /**
     * @throws Exception
     */
    public function testCharge_Success()
    {
        $purchaseEntity = $this->createConfiguredMock(PurchaseEntity::class, [
            'getTotalAmount' => Money::fromString('100.00'),
            'getCurrency' => Currency::EUR,
        ]);

        $paymentEntity = new PaymentEntity($purchaseEntity, PaymentProcessor::STRIPE->value);
        $paymentEntity->setErrorMessage(null);

        $paymentResponse = new StripePaymentResponse(
            StatusEnum::CREATED,
            'PAYPAL_TEST_12345'
        );

        $this->adapter
            ->expects($this->once())
            ->method('pay')
            ->willReturn($paymentResponse);

        $this->service->charge(new PaymentRequest($purchaseEntity, $paymentEntity));

        $this->assertSame($paymentEntity->getAmount()->toString(), $purchaseEntity->getTotalAmount()->toString());
        $this->assertSame($paymentEntity->getCurrency()->name, $purchaseEntity->getCurrency()->name);
        $this->assertSame($paymentEntity->getStatus(), $paymentResponse->status->value);
        $this->assertSame($paymentEntity->getProviderRef(), $paymentResponse->transactionId);

        $this->assertSame($paymentEntity->getRequestBody(), json_encode([
            'amount' => $purchaseEntity->getTotalAmount()->toString(),
            'currency' => $purchaseEntity->getCurrency()
        ]));

        $this->assertNull($paymentEntity->getErrorMessage());
    }

    /**
     * @throws Exception
     */
    public function testCharge_PaymentFailed()
    {
        $purchaseEntity = $this->createConfiguredMock(PurchaseEntity::class, [
            'getTotalAmount' => Money::fromString('100.00'),
            'getCurrency' => Currency::EUR,
        ]);

        $paymentEntity = new PaymentEntity($purchaseEntity, PaymentProcessor::STRIPE->value);
        $paymentEntity->setErrorMessage(null);

        $paymentResponse = new StripePaymentResponse(
            StatusEnum::ERROR, 'STRIPE_TRANS_12345','Test Error message');

        $this->adapter
            ->expects($this->once())
            ->method('pay')
            ->willReturn($paymentResponse);

        $this->service->charge(new PaymentRequest($purchaseEntity, $paymentEntity));

        $this->assertSame($paymentEntity->getAmount()->toString(), $purchaseEntity->getTotalAmount()->toString());
        $this->assertSame($paymentEntity->getCurrency()->name, $purchaseEntity->getCurrency()->name);
        $this->assertSame($paymentEntity->getStatus(), $paymentResponse->status->value);
        $this->assertSame($paymentEntity->getProviderRef(), $paymentResponse->transactionId);

        $this->assertSame($paymentEntity->getRequestBody(), json_encode([
            'amount' => $purchaseEntity->getTotalAmount()->toString(),
            'currency' => $purchaseEntity->getCurrency()
        ]));

        $this->assertSame($paymentEntity->getErrorMessage(), $paymentResponse->errorMessage);
    }
}
