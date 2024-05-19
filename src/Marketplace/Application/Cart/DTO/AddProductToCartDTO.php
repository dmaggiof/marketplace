<?php

namespace Marketplace\Application\Cart\DTO;

class AddProductToCartDTO
{
    public function __construct(private string $productId, private int $quantity, private ?string $customerId, private ?int $cartId = null){}

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getCartId(): ?string
    {
        return $this->cartId;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}