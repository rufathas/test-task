<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Validator;


class CalculatePriceRequestDto
{
    #[Validator\NotBlank]
    #[Validator\Positive]
    #[Validator\Type('integer')]
    public int $product;
    #[Validator\NotBlank]
    public string $taxNumber;
    public string $couponCode;
}
