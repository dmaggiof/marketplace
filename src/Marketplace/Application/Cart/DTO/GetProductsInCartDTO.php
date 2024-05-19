<?php

namespace Marketplace\Application\Cart\DTO;

class GetProductsInCartDTO
{
    public function __construct(private ?string $customerId = null, private ?int $cartId = null){}

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function getCartId(): ?int
    {
        return $this->cartId;
    }

}