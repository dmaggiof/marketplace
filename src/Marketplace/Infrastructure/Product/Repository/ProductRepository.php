<?php

namespace Marketplace\Infrastructure\Product\Repository;

use Marketplace\Domain\Product\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Marketplace\Domain\Product\Repository\ProductRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    private \Doctrine\ORM\EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
        $this->manager = $this->getEntityManager();
    }

    public function save(Product $product){
        $this->manager->persist($product);
        $this->manager->flush();
    }
}
