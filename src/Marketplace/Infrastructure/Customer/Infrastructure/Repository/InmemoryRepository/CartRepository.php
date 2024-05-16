<?php

namespace Marketplace\Infrastructure\Customer\Infrastructure\Repository\InmemoryRepository;

use Marketplace\Domain\Customer\Repository\CartRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Marketplace\Domain\Customer\Entity\Cart;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository  implements CartRepositoryInterface
{
    private array $fakeData = [];
    public function __construct()
    {
        $this->fakeData = [];
    }

    public function findById(int $id): ?Cart
    {
        foreach ($this->fakeData as $model) {
            if ($model->getId() == $id) {
                return $model;
            }
        }
        return null;
    }

    public function save(Cart $cart)
    {
        $this->fakeData[] = $cart;
    }
}
