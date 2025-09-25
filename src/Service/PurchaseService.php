<?php

namespace App\Service;

use App\Dto\PurchaseRequestDto;

interface PurchaseService
{
    public function purchase(PurchaseRequestDto $requestDto): void;
}
