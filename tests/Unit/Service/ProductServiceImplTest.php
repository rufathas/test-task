<?php

namespace App\Tests\Unit\Service;

use App\Dao\Entity\ProductEntity;
use App\Dao\ValueObject\Money;
use App\Enum\ExceptionEnum;
use App\Exception\NotFoundException;
use App\Repository\ProductRepository;
use App\Service\Impl\ProductServiceImpl;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ProductServiceImplTest extends TestCase
{
    private ProductRepository $repo;
    private ProductServiceImpl $service;
    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->repo = $this->createMock(ProductRepository::class);
        $this->service = new ProductServiceImpl($this->repo);
    }

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function testGetById_ReturnsEntity()
    {
        $productEntity = $this->createConfiguredMock(ProductEntity::class, [
            'getId' => 1,
            'getName' => 'Test Product',
            'getPrice' => Money::fromString('100.00')
        ]);

        $this->repo
            ->expects($this->once())
            ->method('findOrFail')->willReturn($productEntity);

        $result = $this->service->getById(1);

        $this->assertSame($result->getPrice(), $productEntity->getPrice());
        $this->assertSame($result->getName(), $productEntity->getName());
        $this->assertSame($result->getPrice()->toString(), $productEntity->getPrice()->toString());
    }

    public function testGetById_ThrowsNotFound(): void
    {
        $this->repo->method('findOrFail')->willThrowException(
            new NotFoundException(ExceptionEnum::PRODUCT_NOT_FOUND)
        );
        $this->expectExceptionObject(new NotFoundException(ExceptionEnum::PRODUCT_NOT_FOUND));

        $this->service->getById(999);
    }

}
