<?php

namespace Marketplace\Infrastructure\Cart\Controllers;

use Marketplace\Application\Cart\GetProductsInCart;
use Marketplace\Domain\Cart\DTO\GetProductsInCartDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_details')]
    public function index(GetProductsInCart $service): Response
    {
        $dto = new GetProductsInCartDTO(1,1);
        $data = $service->execute($dto);
        $products = $data->getProducts();
        $cartTotal = $data->cartTotal;
        return $this->render('Cart/Templates/cart_details.html.twig', [
            'products' => $products,
            'cartTotal' => $cartTotal
        ]);
    }
}
