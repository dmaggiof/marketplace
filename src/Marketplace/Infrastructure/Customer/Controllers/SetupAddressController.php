<?php

namespace Marketplace\Infrastructure\Customer\Controllers;

use Marketplace\Application\Customer\DTO\SetupNewAddressDTO;
use Marketplace\Application\Customer\SetupNewAddress;
use Marketplace\Infrastructure\Customer\Infrastructure\Form\Type\SetupNewAddressType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SetupAddressController extends AbstractController
{
    #[Route(path: '/setup_address', name: 'setup_address')]
    public function index(Request $request, SetupNewAddress $setupNewAddressService): Response
    {
        $form = $this->createForm(SetupNewAddressType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
            $customer = $this->getUser();
            $address = $form->get('address')->getData();


            $setupNewAddressDTO = new SetupNewAddressDTO($customer->getId(), $address);
            $setupNewAddressService->execute($setupNewAddressDTO);


            return $this->redirectToRoute('list_products');
        }

        return $this->render('Customer/Templates/setup_address.html.twig', [
            'form' => $form->createView(),
            'error' => $form->getErrors()
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
