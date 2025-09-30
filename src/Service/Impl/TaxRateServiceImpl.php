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
    public function getTaxRateByTaxNumber(string $taxNumber): TaxRateEntity
    {
        $countryCode = $this->getCountryCodeByTaxNumber(taxNumber: $taxNumber);
        return $this->taxRateRepository->findOneByCountryAndMaskOrFail(
            country: $countryCode,
            mask: $this->maskTaxNumber(taxNumber: $taxNumber)
        );
    }

    private function maskTaxNumber(string $taxNumber): string
    {
        $head = mb_substr($taxNumber, 0, 2, 'UTF-8');
        $tail = mb_substr($taxNumber, 2, null, 'UTF-8');

        //находит любую букву Unicode
        $tail = preg_replace('/\p{L}/u', 'Y', $tail);
        //находит любую цифру Unicode
        $tail = preg_replace('/\p{N}/u', 'X', $tail);

        return $head . $tail;
    }

    public function getCountryCodeByTaxNumber(string $taxNumber): string
    {
        return substr($taxNumber, 0, 2);
    }
}
