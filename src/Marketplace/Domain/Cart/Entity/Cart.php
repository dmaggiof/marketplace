<?php

namespace Marketplace\Domain\Cart\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Marketplace\Domain\Cart\Exceptions\CantAddProductsToFinishedCart;
use Marketplace\Domain\Cart\Exceptions\CantEditAddressOnFinishedCart;
use Marketplace\Domain\Cart\Exceptions\CantHaveMoreThanThreeProductsInCart;
use Marketplace\Domain\Customer\Entity\Customer;
use Marketplace\Domain\Product\Entity\Product;
use Marketplace\Domain\ProductCart\Entity\ProductCart;

class Cart
{
    const FINISHED_CART = 'finished';
    const PENDING_CART = 'pending';
    private ?int $id = null;

    private ?Customer $customer_id = null;
    private Collection $productCarts;
    private ?string $status = null;
    private ?string $address = null;

    public function __construct(?Customer $customer = null)
    {
        $this->productCarts = new ArrayCollection();
        $this->customer_id = $customer;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getCustomerId(): ?Customer
    {
        return $this->customer_id;
    }

    public function setCustomerId(?Customer $customer_id): static
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    /**
     * @return Collection<int, ProductCart>
     */
    public function getProductCarts(): Collection
    {
        return $this->productCarts;
    }

    public function addProductToCart(Product $product, int $quantity): static
    {
        if ($this->status === self::FINISHED_CART) {
            throw new CantAddProductsToFinishedCart();
        }

        if ($this->getProductCarts()->count() === 3 && !$this->cartContainsProduct($product)) {
            throw new CantHaveMoreThanThreeProductsInCart();
        }

        if (!$this->cartContainsProduct($product)) {
            $productCart = new ProductCart();
            $productCart->setCart($this);
            $productCart->setProduct($product);
            $productCart->setQuantity($quantity);
            $productCart->setPrice($product->getPrice());
            if (!$this->status) {
                $this->status=self::PENDING_CART;
            }
            $this->productCarts->add($productCart);

            $this->addProductCart($productCart);
        } else {
            $cartItems = $this->getProductCarts()->filter(function(ProductCart $cartItem) use ($product) {
                return $cartItem->getProduct()->getId() === $product->getId();
            });
            if ($cartItems->count()) {
                foreach ($cartItems as $cartItem) {
                    $cartItem->setQuantity($cartItem->getQuantity() + $quantity);
                }
            }
        }

        return $this;
    }

    public function removeProductFromCart(Product $product): static
    {
        /** @var ProductCart $productCart */
        foreach ($this->productCarts as $productCart) {
            if ($productCart->getProduct()->getId() == $product->getId()){
                $this->removeProductCart($productCart);
            }
        }
        return $this;
    }

    public function addProductCart(ProductCart $productCart): static
    {
        if (!$this->productCarts->contains($productCart)) {
            $this->productCarts->add($productCart);
            $productCart->setCart($this);
        }

        return $this;
    }

    public function removeProductCart(ProductCart $productCart): static
    {
        $this->productCarts->removeElement($productCart);
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        if ($this->status === self::FINISHED_CART) {
            throw new CantEditAddressOnFinishedCart;
        }
        $this->address = $address;
    }

    /**
     * @throws CantEditAddressOnFinishedCart
     */
    public function markAsCompleted(): void
    {
        if (is_null($this->address)) {
            $this->setAddress($this->customer_id->getCustomerDefaultAddress()->getAddress());
        }
        $this->setStatus(self::FINISHED_CART);
    }

    private function cartContainsProduct(Product $product): bool
    {
        foreach ($this->getProductCarts() as $productCart) {
            if ($productCart->getProduct()->getId() === $product->getId()) {
                return true;
            }
        }
        return false;
    }

}
