<?php

namespace App\Tests\Unit\Service;

use App\Dao\Entity\TaxRateEntity;
use App\Enum\ExceptionEnum;
use App\Exception\NotFoundException;
use App\Repository\TaxRateRepository;
use App\Service\Impl\TaxRateServiceImpl;
use App\Service\TaxRateService;
use Brick\Math\BigDecimal;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class TaxRateServiceImplTest extends TestCase
{
    private TaxRateRepository $repo;
    private TaxRateService $service;
    protected function setUp(): void
    {
        $this->repo = $this->createMock(TaxRateRepository::class);
        $this->service = new TaxRateServiceImpl($this->repo);
    }

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function testGetTaxRateByTaxNumber_ReturnsEntity()
    {
        $taxNumber = 'DEAC3456789';

        $taxRateEntity = $this->createConfiguredMock(TaxRateEntity::class, [
            'getId' => 1,
            'getCountry' => 'DE',
            'getRate' => BigDecimal::of('15.00'),
            'getMask' => 'DEYYXXXXXXX',
        ]);

        $this->repo
            ->expects($this->once())
            ->method('findOneByCountryAndMaskOrFail')
            ->with(
                $this->equalTo('DE'),
                $this->equalTo('DEYYXXXXXXX')
            )->willReturn($taxRateEntity);

        $result = $this->service->getTaxRateByTaxNumber($taxNumber);

        $this->assertSame($result->getId(), $taxRateEntity->getId());
        $this->assertSame($result->getCountry(), $taxRateEntity->getCountry());
        $this->assertSame($result->getRate()->__toString(), $taxRateEntity->getRate()->__toString());
        $this->assertSame($result->getMask(), $taxRateEntity->getMask());
    }

    /**
     * @throws Exception
     * @throws NotFoundException
     */
    public function testGetTaxRateByTaxNumber_ThrowsNotFound()
    {
        $taxNumber = 'DEAC3456789';

        $this->repo->method('findOneByCountryAndMaskOrFail')->willThrowException(
            new NotFoundException(ExceptionEnum::COUNTRY_RATE_NOT_FOUND)
        );

        $this->expectExceptionObject(new NotFoundException(ExceptionEnum::COUNTRY_RATE_NOT_FOUND));

        $this->service->getTaxRateByTaxNumber($taxNumber);
    }

    public function testGetCountryCodeByTaxNumber_ReturnsString()
    {
        $taxNumber = 'DEAC3456789';
        $result = $this->service->getCountryCodeByTaxNumber($taxNumber);
        $this->assertSame('DE', $result);
    }
}
