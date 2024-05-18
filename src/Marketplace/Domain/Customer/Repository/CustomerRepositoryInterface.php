<?php

namespace Marketplace\Domain\Customer\Repository;

use Marketplace\Domain\Customer\Entity\Customer;

interface CustomerRepositoryInterface
{
    public function save(Customer $customer);
    public function findOneById(string $id): ?Customer;
}