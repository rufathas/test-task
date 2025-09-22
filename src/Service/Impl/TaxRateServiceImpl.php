<?php

namespace App\Service\Impl;

use App\Dao\Entity\TaxRateEntity;
use App\Exception\NotFoundException;
use App\Repository\TaxRateRepository;
use App\Service\TaxRateService;

class TaxRateServiceImpl implements TaxRateService
{

    public function __construct(
        private readonly TaxRateRepository $taxRateRepository,
    )
    {}

    /**
     * @throws NotFoundException
     */
    public function getByTaxNumber(string $taxNumber): TaxRateEntity
    {
        //TODO: add parsing of tax number to get country code
        return $this->taxRateRepository->findOneByCountryOrFail(country: $taxNumber);
    }
}
