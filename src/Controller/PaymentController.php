<?php

namespace App\Controller;

use App\Dto\CalculatePriceRequestDto;
use App\Resource\CalculatePriceResource;
use App\Service\PriceCalculatorService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PaymentController extends BaseController
{
    public function __construct(
        private readonly PriceCalculatorService $priceCalculatorService
    )
    {}

    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    public function calculatePrice(
        #[MapRequestPayload] CalculatePriceRequestDto $requestDto
    ): JsonResponse
    {
        $response = $this->priceCalculatorService->calculatePrice(
            $requestDto->product,
            $requestDto->taxNumber,
            $requestDto->couponCode
        );

       return $this->successDataResponse(new CalculatePriceResource($response));
    }
}
