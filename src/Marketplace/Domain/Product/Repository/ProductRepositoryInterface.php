<?php

namespace Marketplace\Domain\Product\Repository;

use Marketplace\Domain\Product\Entity\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product);

    public function findAllWithStock(array $array);

    public function findOneById(string $id):?Product;
}