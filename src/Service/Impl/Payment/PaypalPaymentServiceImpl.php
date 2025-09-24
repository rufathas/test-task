<?php

namespace App\Service\Impl\Payment;

use App\Dao\Entity\PaymentEntity;
use App\Dao\ValueObject\Money;
use App\Dto\PurchaseDto;
use App\Exception\PaymentException;
use App\Service\PaymentService;

class PaypalPaymentServiceImpl implements PaymentService
{
    /**
     * @throws PaymentException
     */
    public function pay(Money $amount, PaymentEntity $paymentEntity): PurchaseDto
    {

    }
}
