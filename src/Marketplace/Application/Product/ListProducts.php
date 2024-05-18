<?php

namespace Marketplace\Application\Product;

use Marketplace\Domain\Product\Repository\ProductRepositoryInterface;

class ListProducts
{
    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {}

    public function execute(): array
    {
        return $this->productRepository->findAllWithStock(['stock_quantity' => '0']);

    }
}