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

    public function maskTaxNumber(string $taxNumber): string
    {
        //находит любую букву Unicode после первых 2 символов
        $patternLetters = '/(?<=^.{2})\p{L}/u';
        //находит любую цифру Unicode после первых 2 символов
        $patternDigits  = '/(?<=^.{2})\p{N}/u';
        return preg_replace([$patternLetters, $patternDigits], ['Y', 'X'], $taxNumber);
    }

    public function getCountryCodeByTaxNumber(string $taxNumber): string
    {
        return substr($taxNumber, 0, 2);
    }
}
