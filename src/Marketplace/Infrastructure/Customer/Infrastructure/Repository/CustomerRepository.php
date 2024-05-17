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
}
