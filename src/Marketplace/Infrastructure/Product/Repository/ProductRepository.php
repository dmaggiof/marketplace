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

    public function findAllWithStock(array $array)
    {
        $query = $this->manager->createQuery(
            'SELECT p
    FROM Marketplace\Domain\Product\Entity\Product p
    WHERE p.stock_quantity > 0'
        );
        $products = $query->getResult();
        return $products;
    }

    public function findOneById(string $id): ?Product
    {
        $query = $this->manager->createQuery(
            'SELECT p
    FROM Marketplace\Domain\Product\Entity\Product p
    WHERE p.id = :id'
        )->setParameter('id', $id);
        $product = $query->getOneOrNullResult();
        return $product;
    }
}
