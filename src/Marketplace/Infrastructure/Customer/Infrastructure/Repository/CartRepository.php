<?php

namespace Marketplace\Infrastructure\Customer\Infrastructure\Repository;

use Marketplace\Domain\Customer\Repository\CartRepositoryInterface;
use Marketplace\Domain\Customer\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
