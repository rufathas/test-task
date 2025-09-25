<?php

namespace App\Dto;

use App\Dao\Entity\PurchaseEntity;
use App\Dao\ValueObject\Money;
use App\Enum\Currency;

final class PaymentRequest
{
    public function __construct(
        public readonly PurchaseEntity $purchase,
        public readonly Money $amount,
        public readonly Currency $currency,
        public readonly array $metadata = [],
    ) {}
}
