<?php

namespace Marketplace\Domain\Cart\Interfaces;

use Marketplace\Domain\Cart\Entity\Cart;

interface CartSessionStorageInterface
{
    public function getCart(): ?int;
    public function setCart(Cart $cart): void;
}