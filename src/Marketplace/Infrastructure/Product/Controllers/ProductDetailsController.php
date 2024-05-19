<?php

namespace Marketplace\Infrastructure\Product\Controllers;

use Marketplace\Application\Cart\AddProductToCart;
use Marketplace\Application\Cart\DTO\AddProductToCartDTO;
use Marketplace\Application\Product\ProductDetails;
use Marketplace\Domain\Cart\Exceptions\CantHaveMoreThanThreeProductsInCart;
use Marketplace\Domain\Customer\Exceptions\InsufficientStockForProduct;
use Marketplace\Domain\Product\Exceptions\ProductNotExists;
use Marketplace\Infrastructure\Cart\SessionManager\CartSessionStorage;
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
    public function index(int $id, ProductDetails $productDetailsService, AddProductToCart $addProductToCartService, Request $request, CartSessionStorage $cartSessionStorage): Response
    {
        $product = $productDetailsService->execute($id);
        $form = $this->createForm(AddToCartType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productId = $id;
            $quantity = $form->get('quantity')->getData();
            if ($cartSessionStorage->getCart()) {
                $cartId = $cartSessionStorage->getCart();
            } else {
                $cartId = rand(100,1000);
            }
            $customer = $this->getUser();
            if ($customer) {
                $customerId = $customer->getId();
            } else {
                $customerId = null;
            }

            $addProductToCartDTO = new AddProductToCartDTO($productId, $quantity, $customerId, $cartId);
            try {
                $cartId = $addProductToCartService->execute($addProductToCartDTO);
            } catch (CantHaveMoreThanThreeProductsInCart) {
                return $this->render('Product/Templates/product_details.html.twig', [
                    'product' => $product,
                    'form' => $form->createView(),
                    'error' => 'No puedes añadir más de 3 productos al carrito'
                ]);
            }catch (InsufficientStockForProduct $e) {
                return $this->render('Product/Templates/product_details.html.twig', [
                    'product' => $product,
                    'form' => $form->createView(),
                    'error' => 'No hay suficiente stock del producto '.$e->product
                ]);
            }
            $cartSessionStorage->setCart($cartId);
            return $this->redirectToRoute('cart_details', ['id' => $product->getId()]);
        }

        return $this->render('Product/Templates/product_details.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'error' => ''
        ]);
    }
}
