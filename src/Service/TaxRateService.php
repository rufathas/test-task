<?php

namespace App\Service;

use App\Dao\Entity\TaxRateEntity;

interface TaxRateService
{
    public function getByTaxNumber(string $taxNumber): TaxRateEntity;
}
