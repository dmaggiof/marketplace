<?php

namespace Marketplace\Domain\Customer\Repository;

use Marketplace\Domain\Customer\Entity\Cart;

interface CartRepositoryInterface
{
    public function save(Cart $cart);
}