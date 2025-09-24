<?php

namespace App\Service;

use App\Dao\Entity\PaymentEntity;
use App\Dao\ValueObject\Money;
use App\Dto\PurchaseDto;

interface PaymentService
{
    public function pay(Money $amount, PaymentEntity $paymentEntity): PurchaseDto;
}
