<?php

namespace Marketplace\Infrastructure\Cart\Controllers;

use Marketplace\Application\Cart\DTO\GetProductsInCartDTO;
use Marketplace\Application\Cart\GetProductsInCart;
use Marketplace\Infrastructure\Cart\SessionManager\CartSessionStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_details')]
    public function index(GetProductsInCart $service, CartSessionStorage $cartSessionStorage): Response
    {
        $customer = $this->getUser();
        if ($customer) {
            $customerId = $customer->getId();
            $cartId = $cartSessionStorage->getCart();
        } else {
            $customerId = null;
            $cartId = $cartSessionStorage->getCart();
        }

        $dto = new GetProductsInCartDTO($customerId, $cartId);
        $data = $service->execute($dto);
        $products = $data->getProducts();
        $cartTotal = $data->cartTotal;
        return $this->render('Cart/Templates/cart_details.html.twig', [
            'products' => $products,
            'cartTotal' => $cartTotal
        ]);
    }
}
