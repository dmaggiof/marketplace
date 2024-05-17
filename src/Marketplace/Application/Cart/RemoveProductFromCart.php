<?php
namespace Marketplace\Application\Cart;
use Marketplace\Domain\Cart\DTO\RemoveProductFromCartDTO;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Repository\CartRepositoryInterface;
use Marketplace\Domain\Customer\Repository\CustomerRepositoryInterface;
use Marketplace\Domain\Product\Repository\ProductRepositoryInterface;

class RemoveProductFromCart {
    public function __construct(private readonly CartRepositoryInterface $cartRepository, private readonly ProductRepositoryInterface $productRepository, private readonly CustomerRepositoryInterface $customerRepository)
    {}

    public function execute(RemoveProductFromCartDTO $addProductToCartDTO): void
    {
        $cart = $addProductToCartDTO->getCartId() ? $this->cartRepository->findById($addProductToCartDTO->getCartId()) : null;
        $customer = $this->customerRepository->findOneById($addProductToCartDTO->getCustomerId());

        if (is_null($cart)){
            $cart = new Cart($customer);
        }
        $product = $this->productRepository->findById($addProductToCartDTO->getProductId());
        $cart->removeProductFromCart($product);

        $this->cartRepository->save($cart);
    }
}
