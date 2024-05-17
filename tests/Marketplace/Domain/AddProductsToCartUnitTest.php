<?php


namespace Tests\Marketplace\Domain;

use Exception;
use Marketplace\Domain\Customer\Entity\CustomerAddress;
use Marketplace\Domain\Customer\Repository\CustomerRepositoryInterface;
use Marketplace\Infrastructure\Customer\Infrastructure\Repository\InmemoryRepository\CustomerRepository;
use Marketplace\Domain\Customer\Exceptions\CantHaveMoreThanThreeProductsInCart;
use Marketplace\Domain\Customer\Entity\Cart;
use Marketplace\Domain\Customer\Entity\Customer;
use Marketplace\Domain\Product\Entity\Product;
use Marketplace\Domain\Supplier\Entity\Supplier;
use Marketplace\Infrastructure\Product\Repository\InmemoryProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddProductsToCartUnitTest extends KernelTestCase
{

    /**
     * @throws CantHaveMoreThanThreeProductsInCart
     */
    public function testAddThreeProductsToCart()
    {
        /** @var Customer $customer */
        $customerRepository = new CustomerRepository();
        $productRepository = new InmemoryProductRepository();
        $this->prepareDatabase($customerRepository, $productRepository);

        $customer = ($customerRepository)
            ->findOneByName('Daniel');

        $product1 = $productRepository
            ->findById(1);

        $product2 = $productRepository
            ->findById(2);

        $product3 = $productRepository
            ->findById(3);

        $customer->addProductToCart($product1,1);
        $this->assertEquals(1,$customer->getNumberOfProductsInCart());

        $customer->addProductToCart($product2,1);
        $customer->addProductToCart($product3,1);
        $this->assertEquals(3,$customer->getNumberOfProductsInCart());

        $customerRepository->save($customer);

        $customer = $customerRepository
            ->findOneByName('Daniel');

        $this->assertSame('dmaggio@dmaggio.com', $customer->getEmail());
    }

    /**
     * @throws CantHaveMoreThanThreeProductsInCart
     */
    public function testItShouldFailAddingMoreThan3ProductsToCart()
    {

        $customerRepository = new CustomerRepository();
        $productRepository = new InmemoryProductRepository();
        $this->prepareDatabase($customerRepository, $productRepository);
        /** @var Customer $customer */
        $customer = $customerRepository->findOneBy(1);

        $product1 = $productRepository->findById(1);
        $product2 = $productRepository->findById(2);
        $product3 = $productRepository->findById(3);
        $product4 = $productRepository->findById(4);

        $customer->addProductToCart($product1,1);
        $this->assertEquals(1,$customer->getNumberOfProductsInCart());

        $customer->addProductToCart($product2,1);
        $customer->addProductToCart($product3,1);
        $this->assertEquals(3,$customer->getNumberOfProductsInCart());

        $this->expectException(CantHaveMoreThanThreeProductsInCart::class);
        $customer->addProductToCart($product4, 1);
    }

    /**
     * @throws CantHaveMoreThanThreeProductsInCart
     * @throws Exception
     */
    public function testItShouldNotAllowMoreThanOnePendingCart()
    {
        $customerRepository = new CustomerRepository();
        $productRepository = new InmemoryProductRepository();
        $this->prepareDatabase($customerRepository, $productRepository);
        /** @var Customer $customer */
        $customer = $customerRepository->findOneBy(1);

        $product1 = $productRepository->findById(1);
        $customer->addProductToCart($product1,1);
        $this->assertEquals(1,$customer->getNumberOfProductsInCart());

        $cart2 = new Cart($customer);
        $cart2->setStatus('pending');
        $this->expectExceptionMessage("Ya tienes un carrito activo");
        $customer->addCart($cart2);
    }


    private function prepareDatabase(CustomerRepositoryInterface $customerRepository, InmemoryProductRepository $productRepository): void
    {
        $customer = new Customer();
        $customer->setName('Daniel')
            ->setEmail('dmaggio@dmaggio.com')
            ->setPassword('abc123');


        $customerAddress = new CustomerAddress();
        $customerAddress->setAddress('direccion 123 1ºb');
        $customerAddress->setCustomer($customer);

        $customer->setAddress($customerAddress);

        $customerRepository->save($customer);

        $supplier = new Supplier();
        $supplier->setName('proveedor1')
            ->setCif('id-23849')
            ->setPassword('abc123');
        $product1 = new Product();
        $product1->setName('producto1')->setDescription('descripcion producto 1')
            ->setPrice(1000)->setStockQuantity(3)->setSupplierId($supplier);
        $productRepository->save($product1);

        $product2 = new Product();
        $product2->setName('producto2')
            ->setDescription('descripcion producto 2')
            ->setPrice(1000)
            ->setStockQuantity(3)
            ->setSupplierId($supplier);
        $productRepository->save($product2);

        $product3 = new Product();
        $product3->setName('producto3')
            ->setDescription('descripcion producto 3')
            ->setPrice(1000)
            ->setStockQuantity(3)
            ->setSupplierId($supplier);
        $productRepository->save($product3);

        $product4 = new Product();
        $product4->setName('producto4')
            ->setDescription('descripcion producto 4')
            ->setPrice(1000)
            ->setStockQuantity(4)
            ->setSupplierId($supplier);
        $productRepository->save($product4);
    }
}
