<?php
namespace Marketplace\Application\Cart;
use Marketplace\Application\Cart\DTO\RemoveProductFromCartDTO;
use Marketplace\Domain\Cart\Repository\CartRepositoryInterface;
use Marketplace\Domain\Customer\Repository\CustomerRepositoryInterface;
use Marketplace\Domain\Product\Repository\ProductRepositoryInterface;

class RemoveProductFromCart {
    public function __construct(private readonly CartRepositoryInterface $cartRepository, private readonly ProductRepositoryInterface $productRepository, private readonly CustomerRepositoryInterface $customerRepository)
    {}

    public function execute(RemoveProductFromCartDTO $addProductToCartDTO): void
    {

        if ($addProductToCartDTO->getCustomerId()) {
            $customer = $this->customerRepository->findOneById($addProductToCartDTO->getCustomerId());

            if ($customer) {
                $cart = $customer->getPendingCart();
            }
        }

        if (empty($cart)){
            $cart = $this->cartRepository->findOneById($addProductToCartDTO->getCartId());
            if (empty($cart)) {
                return;
            }
        }

        if (is_null($cart)){
            return;
        }
        $product = $this->productRepository->findOneById($addProductToCartDTO->getProductId());
        $cart->removeProductFromCart($product);
//dd($cart);
        $this->cartRepository->save($cart);
    }
}
