<?php

namespace App\Controller;

use App\Dto\PurchaseRequestDto;
use App\Service\PurchaseService;
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
    )
    {
        $this->purchaseService->purchase($requestDto);
    }
}
