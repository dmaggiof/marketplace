<?php

namespace Marketplace\Infrastructure\Product\Controllers;

use Marketplace\Application\Cart\DTO\RemoveProductFromCartDTO;
use Marketplace\Application\Cart\RemoveProductFromCart;
use Marketplace\Domain\Product\Exceptions\ProductNotExists;
use Marketplace\Infrastructure\Cart\SessionManager\CartSessionStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteProductFromCartController extends AbstractController
{
    /**
     * @throws ProductNotExists
     * @throws \Exception
     */
    #[Route('/deleteProductFromCart/{id}', name: 'remove_product_from_cart')]
    public function index(int $id , RemoveProductFromCart $removeProductFromCartService, CartSessionStorage $cartSessionStorage): Response
    {
        $productId = $id;
        $cartId = $cartSessionStorage->getCart();
        $customer = $this->getUser();
        if ($customer) {
            $customerId = $customer->getId();
        } else {
            $customerId = null;
        }
        $addProductToCartDTO = new RemoveProductFromCartDTO($productId, $customerId,$cartId);
        $removeProductFromCartService->execute($addProductToCartDTO);

        return $this->redirectToRoute('cart_details');


    }
}
