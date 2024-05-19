<?php

namespace Tests\Marketplace\Application;

use Exception;
use Marketplace\Application\Cart\AddProductToCart;
use Marketplace\Domain\Cart\DTO\AddProductToCartDTO;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Exceptions\CantAddProductsToFinishedCart;
use Marketplace\Domain\Cart\Exceptions\CantHaveMoreThanThreeProductsInCart;
use Marketplace\Domain\Customer\Entity\Customer;
use Marketplace\Domain\Customer\Entity\CustomerAddress;
use Marketplace\Domain\Customer\Repository\CustomerRepositoryInterface;
use Marketplace\Domain\Product\Entity\Product;
use Marketplace\Domain\Product\Exceptions\ProductNotExists;
use Marketplace\Domain\Supplier\Entity\Supplier;
use Marketplace\Infrastructure\Cart\Repository\InmemoryRepository\CartRepository;
use Marketplace\Infrastructure\Customer\Infrastructure\Repository\InmemoryRepository\CustomerRepository;
use Marketplace\Infrastructure\Product\Repository\InmemoryProductRepository;
use PHPUnit\Framework\TestCase;

class AddProductToCartTest extends TestCase
{
    public function testAddOneProductToCartForExistingUser()
    {
        /** @var Customer $customer */
        $customerRepository = new CustomerRepository();
        $productRepository = new InmemoryProductRepository();
        $cartRepository = new CartRepository();
        $this->prepareDatabase($customerRepository, $productRepository);

        $product1 = 1;
        $customer = 1;

        $service = new AddProductToCart($cartRepository, $productRepository, $customerRepository);

        $productDTO = new AddProductToCartDTO($product1, 1, $customer, null);
        $service->execute($productDTO);

        $cart = $cartRepository->findById(1);
        $this->assertNotNull($cart);
        $this->assertEquals(1, count($cart->getProductCarts()));
        $firstProductInCart = $cart->getProductCarts()->first();
        $this->assertEquals('producto1', $firstProductInCart->getProduct()->getName());
    }

    public function testItShouldFailAddingNotExistentProduct()
    {
        /** @var Customer $customer */
        $customerRepository = new CustomerRepository();
        $productRepository = new InmemoryProductRepository();
        $cartRepository = new CartRepository();
        $this->prepareDatabase($customerRepository, $productRepository);

        $product1 = 9999;
        $customer = 1;

        $service = new AddProductToCart($cartRepository, $productRepository, $customerRepository);

        $productDTO = new AddProductToCartDTO($product1, 1, $customer, null);

        $this->expectException(ProductNotExists::class);
        $service->execute($productDTO);
    }

    /**
     * @throws ProductNotExists
     */
    public function testItMustFailAddingProductsToFinishedCart()
    {
        /** @var Customer $customer */
        $customerRepository = new CustomerRepository();
        $productRepository = new InmemoryProductRepository();
        $cartRepository = new CartRepository();
        $this->prepareDatabase($customerRepository, $productRepository);
        $this->prepareFinishedCart($cartRepository);

        $product1 = $productRepository->findOneById(1);
        $customer = 1;

        $cart = $cartRepository->findOneById(1);
        $this->expectException(CantAddProductsToFinishedCart::class);
        $cart->addProductToCart($product1,1);
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

    private function prepareFinishedCart(CartRepository $cartRepository)
    {
        $cart = new Cart();
        $cart->setId(1);
        $cart->setStatus(CART::FINISHED_CART);
        $cartRepository->save($cart);
    }
}
