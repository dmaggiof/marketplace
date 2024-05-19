<?php
namespace Marketplace\Application\Cart;
use Marketplace\Application\Cart\DTO\AddProductToCartDTO;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Exceptions\CantAddProductsToFinishedCart;
use Marketplace\Domain\Cart\Exceptions\CantHaveMoreThanThreeProductsInCart;
use Marketplace\Domain\Cart\Repository\CartRepositoryInterface;
use Marketplace\Domain\Customer\Repository\CustomerRepositoryInterface;
use Marketplace\Domain\Product\Exceptions\ProductNotExists;
use Marketplace\Domain\Product\Repository\ProductRepositoryInterface;

class AddProductToCart {
    public function __construct(private readonly CartRepositoryInterface $cartRepository, private readonly ProductRepositoryInterface $productRepository, private readonly CustomerRepositoryInterface $customerRepository)
    {}

    /**
     * @throws ProductNotExists
     * @throws CantAddProductsToFinishedCart
     * @throws CantHaveMoreThanThreeProductsInCart
     */
    public function execute(AddProductToCartDTO $addProductToCartDTO): Cart
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
                $cart = new Cart();
                $cart->setStatus(Cart::PENDING_CART);
            }
        }

        $product = $this->productRepository->findOneById($addProductToCartDTO->getProductId());

        if (!$product) {
            throw new ProductNotExists();
        }
        $cart->addProductToCart($product, $addProductToCartDTO->getQuantity());

        $this->cartRepository->save($cart);
        return $cart;
    }
}
