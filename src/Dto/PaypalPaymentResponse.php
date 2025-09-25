<?php

namespace App\Dto;

use App\Payments\Paypal\Enum\StatusEnum;

class PaypalPaymentResponse
{
    public function __construct(
        public StatusEnum $status,
        public string $transactionId,
        public ?string $errorMessage = null,
    ){}
}
