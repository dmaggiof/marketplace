<?php

namespace Marketplace\Domain\Product\Repository;

use Marketplace\Domain\Product\Entity\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product);
}