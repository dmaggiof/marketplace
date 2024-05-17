<?php

namespace Marketplace\Domain\Cart\Repository;

use Marketplace\Domain\Cart\Entity\Cart;

interface CartRepositoryInterface
{
    public function save(Cart $cart);
}