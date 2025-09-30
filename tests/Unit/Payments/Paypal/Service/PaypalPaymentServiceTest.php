<?php

namespace App\Tests\Unit\Payments\Paypal\Service;

use App\Dao\Entity\PaymentEntity;
use App\Dao\Entity\PurchaseEntity;
use App\Dao\ValueObject\Money;
use App\Dto\PaymentRequest;
use App\Enum\Currency;
use App\Enum\PaymentProcessor;
use App\Payments\Paypal\Adapter\PaypalPaymentProcessorAdapter;
use App\Payments\Paypal\Dto\PaypalPaymentResponse;
use App\Payments\Paypal\Enum\StatusEnum;
use App\Payments\Paypal\Service\PaypalPaymentService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class PaypalPaymentServiceTest extends TestCase
{
    private PaypalPaymentService $service;
    private PaypalPaymentProcessorAdapter $adapter;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->adapter = $this->createMock(PaypalPaymentProcessorAdapter::class);
        $this->service = new PaypalPaymentService($this->adapter);
    }

    public function testSupports_True()
    {
        $result = $this->service->supports(PaymentProcessor::PAYPAL);
        $this->assertTrue($result);
    }

    public function testSupports_False()
    {
        $result = $this->service->supports(PaymentProcessor::STRIPE);
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

        $paymentEntity = new PaymentEntity($purchaseEntity, PaymentProcessor::PAYPAL->value);
        $paymentEntity->setErrorMessage(null);

        $paymentResponse = new PaypalPaymentResponse(
            StatusEnum::COMPLETED,
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
        $this->assertSame($paymentEntity->getRequestBody(), json_encode(['amount' => $purchaseEntity->getTotalAmount()->toString()]));
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

        $paymentEntity = new PaymentEntity($purchaseEntity, PaymentProcessor::PAYPAL->value);
        $paymentEntity->setErrorMessage(null);

        $paymentResponse = new PaypalPaymentResponse(
            StatusEnum::FAILED,
            'PAYPAL_TEST_12345',
            'Test error'
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
        $this->assertSame($paymentEntity->getRequestBody(), json_encode(['amount' => $purchaseEntity->getTotalAmount()->toString()]));
        $this->assertSame($paymentEntity->getErrorMessage(), $paymentResponse->errorMessage);
    }
}
