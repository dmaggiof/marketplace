<?php

namespace Marketplace\Infrastructure\Product\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Product\Entity\Product;
use Marketplace\Domain\Product\Repository\ProductRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class InmemoryProductRepository implements ProductRepositoryInterface
{
    private array $fakeData = [];
    public function __construct()
    {
        $this->fakeData = [];
    }

    public function findById(int $id): ?Product
    {
        foreach ($this->fakeData as $model) {
            if ($model->getId() == $id) {
                return $model;
            }
        }
        return null;
    }

    public function findOneById(string $id): ?Product
    {
        foreach ($this->fakeData as $model) {
            if ($model->getId() == $id) {
                return $model;
            }
        }
        return null;
    }

    public function save(Product $product)
    {
        $product->setId(count($this->fakeData)+1);
        $this->fakeData[$product->getId()] = $product;
    }

    public function findAllWithStock(array $array)
    {
        // TODO: Implement findAllWithStock() method.
    }
}
