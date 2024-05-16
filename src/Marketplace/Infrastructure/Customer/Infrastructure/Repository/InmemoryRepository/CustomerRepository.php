<?php

namespace Marketplace\Infrastructure\Customer\Infrastructure\Repository\InmemoryRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Marketplace\Domain\Customer\Entity\Cart;
use Marketplace\Domain\Customer\Entity\Customer;
use Marketplace\Domain\Customer\Repository\CustomerRepositoryInterface;
use function PHPUnit\Framework\isNull;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CustomerRepository implements CustomerRepositoryInterface
{
    private array $fakeData = [];
    public function __construct()
    {
        $this->fakeData = [];
    }

    public function findOneBy(int $id): ?Customer
    {
        foreach ($this->fakeData as $model) {
            if ($model->getId() == $id) {
                return $model;
            }
        }
        return null;
    }
    public function findOneByName(string $name): ?Customer
    {
        foreach ($this->fakeData as $model) {
            if ($model->getName() == $name) {
                return $model;
            }
        }
        return null;
    }

    public function save(Customer $customer)
    {
        if (is_null($customer->getId())){
            $customer->setId(count($this->fakeData)+1);
        }
        $this->fakeData[] = $customer;
    }

    private function getNextId(object $object): int
    {
        $nextId = 0;

        foreach ($this->fakeData as $currentObject) {
            $nextId = \max($nextId, $currentObject->getId());
        }

        ++$nextId;

        return $nextId;
    }
}
