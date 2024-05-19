<?php

namespace Marketplace\Infrastructure\Customer\Controllers;


use Marketplace\Infrastructure\Cart\Repository\CartRepository;
use Marketplace\Infrastructure\Cart\SessionManager\CartSessionStorage;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private CartSessionStorage $sessionStorage;
    private CartRepository $cartRepository;
    use TargetPathTrait;
    public function __construct(CartSessionStorage $sessionStorage, CartRepository $cartRepository){
        $this->sessionStorage = $sessionStorage;
        $this->cartRepository = $cartRepository;
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?Response
    {
        $cartId = $this->sessionStorage->getCart();
        if ($cartId) {
            $cart = $this->cartRepository->findOneById($cartId);
            $user = $token->getUser();
            $cart->setCustomerId($user);
            $this->cartRepository->save($cart);
        }
        return new RedirectResponse('/');
    }
}