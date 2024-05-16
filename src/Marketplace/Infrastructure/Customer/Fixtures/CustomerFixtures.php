<?php

namespace CustomerFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Marketplace\Domain\Customer\Entity\Customer;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $customer = new Customer();
        $customer->setEmail('dmaggio@dmaggio.com');
        $customer->setName('Daniel');
        $customer->setPassword('abc123');
        $manager->persist($customer);
        $manager->flush();
    }
}
