<?php

namespace App\Controller;

use App\Dto\CalculatePriceRequestDto;
use App\Service\PriceCalculatorService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends BaseController
{
    public function __construct(
        private readonly PriceCalculatorService $priceCalculatorService,
    )
    {}

    #[Route('/calculate-price', name: 'calculate_price')]
    public function calculatePrice(#[MapRequestPayload] CalculatePriceRequestDto $requestDto): Response
    {
        $this->priceCalculatorService->calculatePrice(
            $requestDto->product,
            $requestDto->taxNumber,
            $requestDto->couponCode
        );
    }
}
