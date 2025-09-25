<?php

namespace App\Payments\Stripe\Dto;


use App\Payments\Stripe\Enum\StatusEnum;

class StripePaymentResponse
{
    public function __construct(
        public StatusEnum $status,
        public string $transactionId,
        public ?string $errorMessage = null,
    ){}
}
