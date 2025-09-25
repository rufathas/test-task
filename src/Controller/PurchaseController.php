<?php

namespace App\Controller;

use App\Dto\PurchaseRequestDto;
use App\Service\PurchaseService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends BaseController
{
    public function __construct(
        private readonly PurchaseService $purchaseService,
    )
    {}

    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    public function purchase(
        #[MapRequestPayload] PurchaseRequestDto $requestDto
    ): JsonResponse
    {
        $this->purchaseService->purchase($requestDto);
        return $this->successMessageResponse(
            'Purchase process run successfully',
            Response::HTTP_CREATED
        );
    }
}
