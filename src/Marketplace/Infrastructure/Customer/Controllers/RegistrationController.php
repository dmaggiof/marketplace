<?php

namespace Marketplace\Infrastructure\Customer\Controllers;

use Marketplace\Domain\Customer\Entity\Customer;
use Marketplace\Infrastructure\Customer\Form\CustomerType;
use Marketplace\Infrastructure\Customer\Infrastructure\Repository\CustomerRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'user_registration')]
    public function registerAction(Request $request, CustomerRepository $customerRepository, UserPasswordHasherInterface $passwordHasher)
    {
        // 1) build the form
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordHasher->hashPassword($customer, $customer->getPlainPassword());
            $customer->setPassword($password);

            $customerRepository->save($customer);
            return $this->redirectToRoute('list_products');
        }

        return $this->render(
            'Customer/Templates/register.html.twig',
            array('form' => $form->createView())
        );
    }
}