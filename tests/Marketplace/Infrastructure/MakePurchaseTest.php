<?php


namespace Tests\Marketplace\Infrastructure;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Marketplace\Application\Customer\MakePurchase;
use Marketplace\Domain\Customer\DTO\CustomerPurchasing;
use Marketplace\Domain\Customer\Entity\Customer;
use Marketplace\Domain\Order\Entity\Order;
use Marketplace\Domain\Product\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MakePurchaseTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $rsm = new ResultSetMapping();
        $this->entityManager->createNativeQuery('delete from product_cart;',$rsm)->execute();
        $this->entityManager->createNativeQuery('delete from cart;',$rsm)->execute();

    }

    public function testMakePurchaseOfThreeElements()
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


        $service = new MakePurchase($this->entityManager->getRepository(Customer::class), $this->entityManager->getRepository(Order::class));
        $service->execute(new CustomerPurchasing($customer->getId()));
        $this->assertSame('dmaggio@dmaggio.com', $customer->getEmail());
    }


    protected function tearDown(): void
    {

        $rsm = new ResultSetMapping();
        $this->entityManager->createNativeQuery('delete from product_cart;',$rsm)->execute();
        $this->entityManager->createNativeQuery('delete from cart;',$rsm)->execute();

        parent::tearDown();
        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
