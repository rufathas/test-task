<?php

namespace App\Resource;

use App\Config\Resource\JsonResource;

class CalculatePriceResource extends JsonResource
{
    /**
     * @param array $context
     * @return array
     */
    public function toArray(array $context = []): array
    {
        $calculatePriceDto = $this->data;
        return [
            'productAmount' => $calculatePriceDto->productAmount->toString(),
            'usedCouponAmount' => $calculatePriceDto->usedCouponAmount->toString(),
            'taxPercent' => $calculatePriceDto->taxPercent->__toString(),
            'finalAmount' => $calculatePriceDto->finalAmount->toString(),
        ];
    }
}
