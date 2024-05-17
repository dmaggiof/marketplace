<?php

namespace Marketplace\Infrastructure\Customer\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Marketplace\Domain\Customer\Entity\Cart;
use Marketplace\Domain\Customer\Entity\CustomerAddress;
use Marketplace\Domain\Customer\Repository\CustomerAddressRepositoryInterface;

/**
 * @extends ServiceEntityRepository<CustomerAddress>
 */
class CustomerAddressRepository extends ServiceEntityRepository implements CustomerAddressRepositoryInterface
{
    private \Doctrine\ORM\EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerAddress::class);
        $this->manager =$this->getEntityManager();
    }

    public function save(CustomerAddress $customerAddress){
        $this->manager->persist($customerAddress);
        $this->manager->flush();
    }

}
