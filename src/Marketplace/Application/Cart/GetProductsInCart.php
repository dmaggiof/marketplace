<?php
namespace Marketplace\Application\Cart;
use Doctrine\Common\Collections\Collection;
use Marketplace\Domain\Cart\DTO\CartDetailsDTO;
use Marketplace\Domain\Cart\DTO\GetProductsInCartDTO;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Repository\CartRepositoryInterface;
use Marketplace\Domain\Customer\Repository\CustomerRepositoryInterface;

class GetProductsInCart {
    public function __construct(private readonly CartRepositoryInterface $cartRepository, private readonly CustomerRepositoryInterface $customerRepository)
    {}

    public function execute(GetProductsInCartDTO $addProductToCartDTO): CartDetailsDTO
    {
        $customer = $this->customerRepository->findOneById($addProductToCartDTO->getCustomerId());
        $cart = $this->cartRepository->findOneById($addProductToCartDTO->getCartId());

        if (empty($cart)){
            $cart = new Cart($customer);
        }

        $cartDetailsDto = new CartDetailsDTO($cart->getProductCarts()->toArray());
        return $cartDetailsDto;
    }
}
