<?php

namespace Marketplace\Infrastructure\Product\Controllers;

use Marketplace\Application\Cart\AddProductToCart;
use Marketplace\Application\Cart\RemoveProductFromCart;
use Marketplace\Application\Product\ProductDetails;
use Marketplace\Domain\Cart\DTO\AddProductToCartDTO;
use Marketplace\Domain\Cart\DTO\RemoveProductFromCartDTO;
use Marketplace\Domain\Product\Exceptions\ProductNotExists;
use Marketplace\Infrastructure\Product\Form\Type\AddToCartType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteProductFromCartController extends AbstractController
{
    /**
     * @throws ProductNotExists
     * @throws \Exception
     */
    #[Route('/deleteProductFromCart/{id}', name: 'remove_product_from_cart')]
    public function index(int $id , RemoveProductFromCart $removeProductFromCartService): Response
    {
        $productId = $id;
        $cartId = 1;
        $addProductToCartDTO = new RemoveProductFromCartDTO($productId, 1,$cartId);
        $removeProductFromCartService->execute($addProductToCartDTO);

        return $this->redirectToRoute('cart_details');


    }
}
