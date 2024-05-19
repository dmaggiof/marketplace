<?php

namespace Marketplace\Infrastructure\Cart\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use JetBrains\PhpStorm\NoReturn;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Interfaces\CartSessionStorageInterface;
use Marketplace\Domain\Cart\Repository\CartRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository implements CartRepositoryInterface
{
    private \Doctrine\ORM\EntityManagerInterface $manager;
    private CartSessionStorageInterface $cartSessionStorage;

    public function __construct(ManagerRegistry $registry, CartSessionStorageInterface $cartSessionStorage)
    {
        parent::__construct($registry, Cart::class);
        $this->manager =$this->getEntityManager();
        $this->cartSessionStorage = $cartSessionStorage;
    }

    #[NoReturn] public function save(Cart $cart){
        $this->manager->persist($cart);
        $this->manager->flush();
        $this->cartSessionStorage->setCart($cart);
    }

    public function findOneById(string $id): ?Cart
    {
        $query = $this->manager->createQuery(
            'SELECT p
    FROM Marketplace\Domain\Cart\Entity\Cart p
    WHERE p.id = :id'
        )->setParameter('id', $id);
        $cart = $query->getOneOrNullResult();
        return $cart;
    }
}
