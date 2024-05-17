<?php

namespace Marketplace\Domain\Customer\Repository;

use Marketplace\Domain\Customer\Entity\CustomerAddress;

interface CustomerAddressRepositoryInterface
{
    public function save(CustomerAddress $customerAddress);
}