<?php

namespace Marketplace\Infrastructure\Customer\Infrastructure\Repository\InmemoryRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Marketplace\Domain\Customer\Entity\Cart;
use Marketplace\Domain\Customer\Entity\CustomerAddress;
use Marketplace\Domain\Customer\Repository\CustomerAddressRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CustomerAddressRepository  implements CustomerAddressRepositoryInterface
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

    public function save(CustomerAddress $customerAddress)
    {
        $this->fakeData[] = $customerAddress;
    }
}
