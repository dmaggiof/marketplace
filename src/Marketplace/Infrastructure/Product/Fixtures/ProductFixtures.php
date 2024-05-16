<?php

namespace ProductFixtures;

use App\Factory\Entity\ProductFactory;
use App\Factory\Entity\SupplierFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        SupplierFactory::createMany(2);
        ProductFactory::createMany(10, function() {
            return [
                'supplier_id' => SupplierFactory::random(),
            ];
        });
    }
}
