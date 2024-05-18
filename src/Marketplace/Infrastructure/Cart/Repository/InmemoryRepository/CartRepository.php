<?php

namespace Marketplace\Infrastructure\Cart\Repository\InmemoryRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Repository\CartRepositoryInterface;

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
        if (is_null($cart->getId())){
            $cart->setId(count($this->fakeData)+1);
        }
        $this->fakeData[$cart->getId()] = $cart;
    }

    public function findOneById(string $id): ?Cart
    {
        foreach ($this->fakeData as $model) {
            if ($model->getId() == $id) {
                return $model;
            }
        }
        return null;
    }
}
