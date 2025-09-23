<?php

namespace App\Service;

use App\Dao\Entity\TaxRateEntity;

interface TaxRateService
{
    public function getTaxRateByTaxNumber(string $taxNumber): TaxRateEntity;
}
