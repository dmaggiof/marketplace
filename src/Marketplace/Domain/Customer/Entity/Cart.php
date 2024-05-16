<?php

namespace Marketplace\Domain\Customer\Entity;

use Marketplace\Domain\ProductCart\Entity\ProductCart;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Marketplace\Domain\Product\Entity\Product;

class Cart
{
    private ?int $id = null;


    private ?Customer $customer_id = null;

    private Collection $productCarts;

    private ?string $status = null;
    private ArrayCollection $order_id;
    private ArrayCollection $product;

    public function __construct(Customer $customer)
    {
        $this->order_id = new ArrayCollection();
        $this->product = new ArrayCollection();
        $this->productCarts = new ArrayCollection();
        $this->customer_id = $customer;
    }

    public function getId(): ?int
    {
        return $this->id;
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
        if (!$this->product->contains($product)) {
            $this->product->add($product);
            $productCart = new ProductCart();
            $productCart->setCart($this);
            $productCart->setProduct($product);
            $productCart->setQuantity($quantity);
            $productCart->setPrice($product->getPrice());
            $this->addProductCart($productCart);
        }

        return $this;
    }

    public function removeProductFromCart(Product $product): static
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
            $finder = function($p) use ($product){
                return $product->getId() == $p->getProduct()->getId();
            };
            $element = $this->productCarts->filter($finder);
            $this->productCarts->removeElement($product);
            $this->removeProductCart($element->getValues()[0]);
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
        if ($this->productCarts->removeElement($productCart)) {
            // set the owning side to null (unless already changed)
            if ($productCart->getCart() === $this) {
                $productCart->setCart(null);
            }
        }

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


}
