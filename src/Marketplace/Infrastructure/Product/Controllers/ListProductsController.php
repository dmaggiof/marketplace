<?php

namespace Marketplace\Infrastructure\Product\Controllers;

use Marketplace\Application\Product\ListProducts;
use Marketplace\Infrastructure\Product\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ListProductsController extends AbstractController
{
    #[Route('/', name: 'list_products')]
    public function index(ListProducts $service): Response
    {
        $products = $service->execute();
        return $this->render('Product/Templates/listproducts.html.twig', [
            'products' => $products
        ]);
    }
}
