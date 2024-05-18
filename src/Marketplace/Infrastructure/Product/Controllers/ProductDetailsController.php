<?php

namespace Marketplace\Infrastructure\Product\Controllers;

use Marketplace\Application\Cart\AddProductToCart;
use Marketplace\Application\Product\ProductDetails;
use Marketplace\Domain\Cart\DTO\AddProductToCartDTO;
use Marketplace\Domain\Product\Exceptions\ProductNotExists;
use Marketplace\Infrastructure\Product\Form\Type\AddToCartType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductDetailsController extends AbstractController
{
    /**
     * @throws ProductNotExists
     * @throws \Exception
     */
    #[Route('/product/{id}', name: 'product_details')]
    public function index(int $id, ProductDetails $productDetailsService, AddProductToCart $addProductToCartService, Request $request): Response
    {
        $product = $productDetailsService->execute($id);
        $form = $this->createForm(AddToCartType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productId = $id;
            $quantity = $form->get('quantity')->getData();
            $cartId = 1;
            $addProductToCartDTO = new AddProductToCartDTO($productId, $quantity, 1,$cartId);
            $addProductToCartService->execute($addProductToCartDTO);

            return $this->redirectToRoute('cart_details', ['id' => $product->getId()]);
        }

        return $this->render('Product/Templates/product_details.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
