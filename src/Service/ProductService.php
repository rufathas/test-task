<?php

namespace App\Service;

use App\Dao\Entity\ProductEntity;

interface ProductService
{
    public function getById(int $id): ProductEntity;
}
