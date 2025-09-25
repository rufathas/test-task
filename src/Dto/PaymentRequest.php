<?php

namespace App\Dto;

use App\Dao\Entity\PaymentEntity;
use App\Dao\Entity\PurchaseEntity;

final class PaymentRequest
{
    public function __construct(
        public readonly PurchaseEntity $purchaseEntity,
        public readonly PaymentEntity  $paymentEntity,
        public readonly array          $metadata = [],
    ) {}
}
