<?php

namespace Marketplace\Infrastructure\Customer\Controllers;

use Marketplace\Application\Customer\MakePurchase;
use Marketplace\Domain\Customer\Exceptions\CustomerHasNoAddressConfigured;
use Marketplace\Domain\Customer\Exceptions\CustomerMustSignInToFinishPurchase;
use Marketplace\Domain\Customer\DTO\CustomerPurchasing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MakePurchaseController extends AbstractController
{
    #[Route('/make_purchase', name: 'make_purchase')]
    public function index(MakePurchase $service): Response
    {
        $customer = $this->getUser();
        if ($customer) {
            $customerId = $customer->getId();
        } else {
            return $this->redirectToRoute('app_login');
        }

        $dto = new CustomerPurchasing($customerId);

        try {
            $productsInCart = $service->execute($dto);
        } catch (CustomerHasNoAddressConfigured) {
            return $this->redirectToRoute('setup_address');
        }
        return $this->render('Customer/Templates/purchase_completed.html.twig', [
            'products' => $productsInCart->getProducts(),
            'cartTotal' => $productsInCart->cartTotal,
            'address' => $customer->getCustomerDefaultAddress()->getAddress()
        ]);
    }
}
