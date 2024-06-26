<?php
namespace Marketplace\Application\Cart;
use Marketplace\Application\Cart\DTO\CartDetailsDTO;
use Marketplace\Application\Cart\DTO\GetProductsInCartDTO;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Repository\CartRepositoryInterface;
use Marketplace\Domain\Customer\Repository\CustomerRepositoryInterface;

class GetProductsInCart {
    public function __construct(private readonly CartRepositoryInterface $cartRepository, private readonly CustomerRepositoryInterface $customerRepository)
    {}

    public function execute(GetProductsInCartDTO $addProductToCartDTO): CartDetailsDTO
    {
        $customer = null;
        if ($addProductToCartDTO->getCustomerId()) {
            $customer = $this->customerRepository->findOneById($addProductToCartDTO->getCustomerId());

            if ($customer) {
                $cart = $customer->getPendingCart();
            }
        }
        if (!$customer) {
            if (!$addProductToCartDTO->getCartId()) {
                return new CartDetailsDTO([]);
            }
            $cart = $this->cartRepository->findOneById($addProductToCartDTO->getCartId());
        }
        if (empty($cart)){
            $cart = new Cart($customer);
        }

        $cartDetailsDto = new CartDetailsDTO($cart->getProductCarts()->toArray());
        return $cartDetailsDto;
    }
}
