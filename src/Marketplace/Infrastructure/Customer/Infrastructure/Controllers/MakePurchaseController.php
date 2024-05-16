<?php

namespace Marketplace\Infrastructure\Customer\Infrastructure\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class MakePurchaseController extends AbstractController
{
    #[Route('/marketplace/infrastructure/customer/infrastructure/make/purchase', name: 'app_marketplace_infrastructure_customer_infrastructure_make_purchase')]
    public function index(): JsonResponse
    {

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Marketplace/Infrastructure/Customer/Infrastructure/MakePurchaseController.php',
        ]);
    }
}
