<?php

namespace Marketplace\Infrastructure\Order\Repository;

use Doctrine\Persistence\ObjectManager;
use Marketplace\Domain\Customer\Entity\Customer;
use Marketplace\Domain\Order\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    private ObjectManager $manager;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
        $this->manager =$this->getEntityManager();
    }

    public function save(Order $customer){
        $this->manager->persist($customer);
        $this->manager->flush();
    }
    //    /**
    //     * @return Order[] Returns an array of Order objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
