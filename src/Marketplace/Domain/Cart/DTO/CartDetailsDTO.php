<?php

namespace Marketplace\Domain\Cart\DTO;

use Marketplace\Domain\Product\Entity\Product;
use Marketplace\Domain\ProductCart\Entity\ProductCart;

class CartDetailsDTO
{
    private array $products;
    public readonly ?int $cartTotal;

    public function __construct(array $productsFromDb)
    {
        $cartTotal = 0;
        $this->products = [];
        /** @var ProductCart $productCart */
        foreach ($productsFromDb as $productCart) {
            $this->products[] = [
                'id' => $productCart->getProduct()->getId(),
                'name' => $productCart->getProduct()->getName(),
                'description' => $productCart->getProduct()->getDescription(),
                'quantity' => $productCart->getQuantity(),
                'price' => $productCart->getPriceToShow()*$productCart->getQuantity(),
            ];
            $cartTotal += $productCart->getPriceToShow() * $productCart->getQuantity();
        }
        $this->cartTotal = $cartTotal;
    }

    public function getProducts(): array
    {
        return $this->products;
    }
}