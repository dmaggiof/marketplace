<?php

namespace Marketplace\Application\Product;

use Marketplace\Domain\Product\Entity\Product;
use Marketplace\Domain\Product\Repository\ProductRepositoryInterface;

class ProductDetails
{
    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {}

    public function execute(int $id): Product
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            throw new \Exception("No existe el producto");
        }
        return $product[0];
    }
}