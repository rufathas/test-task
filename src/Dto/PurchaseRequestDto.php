<?php

namespace App\Dto;

use App\Enum\PaymentProcessor;
use Symfony\Component\Validator\Constraints as Validator;


class PurchaseRequestDto
{
    #[Validator\NotBlank]
    #[Validator\Positive]
    #[Validator\Type('integer')]
    public int $product;
    #[Validator\NotBlank]
    public string $taxNumber;
    public ?string $couponCode = null;
    #[Validator\NotBlank]
    public PaymentProcessor $paymentProcessor;
}
