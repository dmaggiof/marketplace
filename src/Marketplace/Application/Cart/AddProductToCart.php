<?php
namespace Marketplace\Application\Cart;
use Marketplace\Domain\Cart\DTO\AddProductToCartDTO;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Repository\CartRepositoryInterface;
use Marketplace\Domain\Customer\Repository\CustomerRepositoryInterface;
use Marketplace\Domain\Product\Exceptions\ProductNotExists;
use Marketplace\Domain\Product\Repository\ProductRepositoryInterface;
use Marketplace\Infrastructure\Cart\Repository\CartRepository;
use Marketplace\Infrastructure\Customer\Infrastructure\Repository\CustomerRepository;
use Marketplace\Infrastructure\Product\Repository\ProductRepository;

class AddProductToCart {
    public function __construct(private readonly CartRepositoryInterface $cartRepository, private readonly ProductRepositoryInterface $productRepository, private readonly CustomerRepositoryInterface $customerRepository)
    {}

    public function execute(AddProductToCartDTO $addProductToCartDTO): void
    {
        $cart = $addProductToCartDTO->getCartId() ? $this->cartRepository->findById($addProductToCartDTO->getCartId()) : null;
        $customer = $this->customerRepository->findOneById($addProductToCartDTO->getCustomerId());

        if (is_null($cart)){
            $cart = new Cart($customer);
        }
        $product = $this->productRepository->findById($addProductToCartDTO->getProductId());

        if (!$product) {
            throw new ProductNotExists();
        }
        $cart->addProductToCart($product, $addProductToCartDTO->getQuantity());

        $this->cartRepository->save($cart);
    }
}