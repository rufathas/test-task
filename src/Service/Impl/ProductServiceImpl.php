<?php

namespace App\Service\Impl;

use App\Dao\Entity\ProductEntity;
use App\Exception\NotFoundException;
use App\Repository\ProductRepository;
use App\Service\ProductService;

class ProductServiceImpl implements ProductService
{

    public function __construct(
        private readonly ProductRepository $productRepository,
    )
    {}


    /**
     * @throws NotFoundException
     */
    public function getById(int $id): ProductEntity
    {
        return $this->productRepository->findOrFail(id: $id);
    }
}
