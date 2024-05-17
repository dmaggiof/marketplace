<?php

namespace Marketplace\Infrastructure\Cart\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Repository\CartRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository implements CartRepositoryInterface
{
    private \Doctrine\ORM\EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
        $this->manager =$this->getEntityManager();
    }

    public function save(Cart $cart){
        $this->manager->persist($cart);
        $this->manager->flush();
    }
}
