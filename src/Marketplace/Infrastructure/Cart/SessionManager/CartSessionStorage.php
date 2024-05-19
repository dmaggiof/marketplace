<?php

namespace Marketplace\Infrastructure\Cart\SessionManager;


use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Interfaces\CartSessionStorageInterface;
use Marketplace\Infrastructure\Cart\Repository\CartRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartSessionStorage implements CartSessionStorageInterface
{
    const CART_KEY_NAME = 'cart_id';
    private $requestStack;



    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getCart(): ?int
    {
        return $this->getCartId();
    }

    public function setCart(Cart $cart): void
    {
        $this->getSession()->set(self::CART_KEY_NAME, $cart->getId());
    }

    private function getCartId(): ?int
    {
        return $this->getSession()->get(self::CART_KEY_NAME);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}