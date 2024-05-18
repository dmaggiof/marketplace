<?php

namespace Tests\Marketplace\Application;

use Marketplace\Application\Cart\AddProductToCart;
use Marketplace\Application\Cart\RemoveProductFromCart;
use Marketplace\Domain\Cart\DTO\AddProductToCartDTO;
use Marketplace\Domain\Cart\DTO\RemoveProductFromCartDTO;
use Marketplace\Domain\Cart\Exceptions\CantAddProductsToFinishedCart;
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

class RemoveProducFromCartTest extends TestCase
{
    /**
     * @throws ProductNotExists
     * @throws CantAddProductsToFinishedCart
     */
    public function testRemoveProductFromCartForExistingUser()
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
        $this->assertCount(1, $cart->getProductCarts());

        $serviceRemover = new RemoveProductFromCart($cartRepository, $productRepository, $customerRepository);
        $productDTO = new RemoveProductFromCartDTO($product1, $customer, 1);
        $serviceRemover->execute($productDTO);

        $cart = $cartRepository->findById(1);
        $this->assertTrue($cart->getProductCarts()->isEmpty());
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
