<?php


namespace Tests\Marketplace\Infrastructure;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Query\ResultSetMapping;
use Marketplace\Application\Customer\MakePurchase;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Exceptions\CantHaveMoreThanThreeProductsInCart;
use Marketplace\Domain\Customer\DTO\CustomerPurchasing;
use Marketplace\Domain\Customer\Entity\Customer;
use Marketplace\Domain\Customer\Entity\CustomerAddress;
use Marketplace\Domain\Customer\Exceptions\CustomerHasNoAddressConfigured;
use Marketplace\Domain\Order\Entity\Order;
use Marketplace\Domain\Product\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MakePurchaseTest extends KernelTestCase
{
    private ?EntityManager $entityManager;

    /**
     * @param Customer $customer
     * @return void
     * @throws CantHaveMoreThanThreeProductsInCart
     * @throws ORMException
     */
    public function addThreeProductsToCart(Customer $customer): void
    {
        $product1 = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy(['id' => 1]);

        $product2 = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy(['id' => 2]);

        $product3 = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy(['id' => 3]);

        $customer->addProductToCart($product1, 1);
        $this->assertEquals(1, $customer->getNumberOfProductsInCart());

        $customer->addProductToCart($product2, 1);
        $customer->addProductToCart($product3, 1);

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

    }

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

    /**
     * @throws OptimisticLockException
     * @throws CantHaveMoreThanThreeProductsInCart
     * @throws ORMException
     */
    public function testMakePurchaseOfThreeElements()
    {
        /** @var Customer $customer */
        $customer = $this->entityManager
            ->getRepository(Customer::class)
            ->findOneBy(['name' => 'Daniel']);

        $this->addAddressToCustomer($customer);
        $this->addThreeProductsToCart($customer);

        $this->assertEquals(3,$customer->getNumberOfProductsInCart());
        $this->assertEquals(CART::PENDING_CART, $customer->getCart()->getStatus());

        $service = new MakePurchase($this->entityManager->getRepository(Customer::class), $this->entityManager->getRepository(Order::class));
        $service->execute(new CustomerPurchasing($customer->getId()));
        $this->assertSame('dmaggio@dmaggio.com', $customer->getEmail());

        $cart = $this->entityManager
            ->getRepository(Cart::class)
            ->findOneBy(['customer_id' => '1']);

        $this->assertNotNull($cart);
        $this->assertEquals('direccion 123 1ºb', $cart->getAddress());
        $this->assertEquals(CART::FINISHED_CART, $cart->getStatus());
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws CantHaveMoreThanThreeProductsInCart
     */
    public function testPurchaseShouldFailWhenCustomerHasNoAddress()
    {
        /** @var Customer $customer */
        $customer = $this->entityManager
            ->getRepository(Customer::class)
            ->findOneBy(['name' => 'Daniel']);


        $customerAddress = $this->entityManager
            ->getRepository(CustomerAddress::class)
            ->findOneBy(['address' => 'direccion 123 1ºb']);

        if ($customerAddress) {
            $this->entityManager->remove($customerAddress);
            $this->entityManager->flush();
        }
        $product1 = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy(['id' => 1]);

        $customer->addProductToCart($product1,1);
        $this->assertEquals(1,$customer->getNumberOfProductsInCart());

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        $service = new MakePurchase($this->entityManager->getRepository(Customer::class), $this->entityManager->getRepository(Order::class));
        $this->expectException(CustomerHasNoAddressConfigured::class);
        $service->execute(new CustomerPurchasing($customer->getId()));
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

    private function addAddressToCustomer(Customer $customer)
    {
        $customerAddress = $this->entityManager
            ->getRepository(CustomerAddress::class)
            ->findOneBy(['address' => 'direccion 123 1ºb']);

        if ($customerAddress) {
            $this->entityManager->remove($customerAddress);
            $this->entityManager->flush();
        }
        $customerAddress = new CustomerAddress();
        $customerAddress->setAddress('direccion 123 1ºb')
            ->setDefaultAddress(true)
            ->setCustomer($customer);
        $this->entityManager->persist($customerAddress);
        $this->entityManager->flush();

    }
}
