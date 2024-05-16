<?php

namespace Marketplace\Infrastructure\Customer\Infrastructure\Repository;

use Doctrine\Persistence\ObjectManager;
use Marketplace\Domain\Customer\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Marketplace\Domain\Customer\Repository\CustomerRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Customer>
 */
class CustomerRepository extends ServiceEntityRepository implements CustomerRepositoryInterface
{
    private ObjectManager $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
        $this->manager =$this->getEntityManager();
    }

    public function save(Customer $customer){
        $this->manager->persist($customer);
        $this->manager->flush();
    }
    //    /**
    //     * @return Customer[] Returns an array of Customer objects
    //     */
        public function findByExampleField($value): array
        {
            return $this->createQueryBuilder('c')
                ->andWhere('c.name = :val')
                ->setParameter('val', $value)
                ->setMaxResults(1)
                ->getQuery()
                ->getResult()
            ;
        }
    //    public function findOneBySomeField($value): ?Customer
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
