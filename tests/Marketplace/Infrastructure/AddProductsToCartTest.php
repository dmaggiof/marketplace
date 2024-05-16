<?php


namespace Tests\Marketplace\Infrastructure;

use Doctrine\ORM\EntityManager;
use Marketplace\Domain\Customer\Entity\Customer;
use Marketplace\Domain\Product\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddProductsToCartTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testAdd3ProductsToCart()
    {
        /** @var Customer $customer */
        $customer = $this->entityManager
            ->getRepository(Customer::class)
            ->findOneBy(['name' => 'Daniel']);

        $product1 = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy(['id' => 1]);

        $product2 = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy(['id' => 2]);

        $product3 = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy(['id' => 3]);

        $customer->addProductToCart($product1,1);
        $this->assertEquals(1,$customer->getNumberOfProductsInCart());

        $customer->addProductToCart($product2,1);
        $customer->addProductToCart($product3,1);
        $this->assertEquals(3,$customer->getNumberOfProductsInCart());

        $this->entityManager->persist($customer);
        $this->entityManager->flush();
        $this->assertSame('dmaggio@dmaggio.com', $customer->getEmail());
    }


    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
