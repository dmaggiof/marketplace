<?php

namespace Marketplace\Infrastructure\Product\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Marketplace\Domain\Customer\Entity\Cart;
use Marketplace\Domain\Product\Entity\Product;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class InmemoryProductRepository
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

    public function save(Product $product)
    {
        $product->setId(count($this->fakeData)+1);
        $this->fakeData[] = $product;
    }
}
