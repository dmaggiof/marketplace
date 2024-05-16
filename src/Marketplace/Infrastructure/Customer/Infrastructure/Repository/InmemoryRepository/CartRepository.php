<?php

namespace Marketplace\Infrastructure\Customer\Infrastructure\Repository\InmemoryRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Marketplace\Domain\Customer\Entity\Cart;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository
{
    private array $fakeData = [];
    public function __construct(ManagerRegistry $registry)
    {
        $this->fakeData = [];
        die("en el repo inmemroy");
    }

    public function findById(int $id): ?static
    {
        foreach ($this->fakeData as $model) {
            if ($model->getId() == $id) {
                return $model;
            }
        }
        return null;
    }
}
