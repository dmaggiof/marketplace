<?php
namespace Marketplace\Application\Cart;
use Marketplace\Application\Cart\DTO\AddProductToCartDTO;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Exceptions\CantAddProductsToFinishedCart;
use Marketplace\Domain\Cart\Exceptions\CantHaveMoreThanThreeProductsInCart;
use Marketplace\Domain\Cart\Repository\CartRepositoryInterface;
use Marketplace\Domain\Customer\Exceptions\InsufficientStockForProduct;
use Marketplace\Domain\Customer\Repository\CustomerRepositoryInterface;
use Marketplace\Domain\Product\Exceptions\ProductNotExists;
use Marketplace\Domain\Product\Repository\ProductRepositoryInterface;
use Psr\Log\LoggerInterface;

class AddProductToCart {
    public function __construct(private readonly CartRepositoryInterface $cartRepository, private readonly ProductRepositoryInterface $productRepository, private readonly CustomerRepositoryInterface $customerRepository, private readonly LoggerInterface $logger)
    {}

    /**
     * @throws ProductNotExists
     * @throws CantAddProductsToFinishedCart
     * @throws CantHaveMoreThanThreeProductsInCart
     * @throws InsufficientStockForProduct
     */
    public function execute(AddProductToCartDTO $addProductToCartDTO): Cart
    {
        if ($addProductToCartDTO->getCustomerId()) {
            $customer = $this->customerRepository->findOneById($addProductToCartDTO->getCustomerId());

            if ($customer) {
                $cart = $customer->getPendingCart();
                $this->logger->info("Cargando carrito del usuario ".$addProductToCartDTO->getCustomerId());
            }
        }
        if (empty($cart)){
            $cart = $this->cartRepository->findOneById($addProductToCartDTO->getCartId());
            if (empty($cart)) {
                $cart = new Cart();
                $this->logger->info("Iniciando nuevo carrito");
                $cart->setStatus(Cart::PENDING_CART);
            }
        }

        $product = $this->productRepository->findOneById($addProductToCartDTO->getProductId());

        if (!$product) {
            throw new ProductNotExists();
        }
        $cart->addProductToCart($product, $addProductToCartDTO->getQuantity());
        $this->logger->info("Producto añadido");

        $this->cartRepository->save($cart);
        return $cart;
    }
}
