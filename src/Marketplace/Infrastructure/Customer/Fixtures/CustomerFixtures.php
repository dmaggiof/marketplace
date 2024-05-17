<?php

namespace CustomerFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Marketplace\Domain\Customer\Entity\Customer;
use Marketplace\Domain\Customer\Entity\CustomerAddress;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $customer = new Customer();
        $customer->setEmail('dmaggio@dmaggio.com');
        $customer->setName('Daniel');
        $customer->setPassword('abc123');

        $customerAddress = new CustomerAddress();
        $customerAddress->setAddress('direccion 123 1ºb');
        $customerAddress->setCustomer($customer);

        $customer->setAddress($customerAddress);

        $manager->persist($customerAddress);
        $manager->persist($customer);
        $manager->flush();
    }
}
