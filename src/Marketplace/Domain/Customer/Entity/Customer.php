<?php

namespace Marketplace\Domain\Customer\Entity;

use Marketplace\Domain\Customer\Exceptions\CantHaveMoreThanThreeProductsInCart;
use Marketplace\Domain\Order\Entity\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Marketplace\Domain\Product\Entity\Product;

class Customer
{
    private ?int $id = null;

    private ?string $name = null;

    private ?string $email = null;

    private ?string $password = null;

    private Collection $orders;

    private Collection $cart;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->cart = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setCustomerId($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomerId() === $this) {
                $order->setCustomerId(null);
            }
        }

        return $this;
    }

    public function getCart(): Cart
    {
        return $this->cart->first();
    }

    public function addCart(Cart $cart): static
    {
        if ($this->cart->isEmpty()) {
            $this->cart->add($cart);
        }
        if (!$this->cart->contains($cart)) {
            $finder = function($c) use ($cart){
                return $cart->getStatus() === 'pending';
            };
            $cartsInPendingStatus = $this->cart->filter($finder);
            if (!empty($cartsInPendingStatus)){
                throw new \Exception("Ya tienes un carrito activo");
            }
            $this->cart->add($cart);
            $cart->setCustomerId($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->cart->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getCustomerId() === $this) {
                $cart->setCustomerId(null);
            }
        }

        return $this;
    }

    public function addProductToCart(Product $product, int $quantity): static
    {
        if ($this->cart->isEmpty()) {
            $cart = new Cart($this);
            $cart->setStatus('pending');
            $this->addCart($cart);
        }
        if ($this->getNumberOfProductsInCart() === 3) {
            throw new CantHaveMoreThanThreeProductsInCart();
        }
        $this->getCart()->addProductToCart($product, $quantity);
        return $this;
    }

    public function removeProductFromCart(Product $product): static
    {
        if ($this->cart->isEmpty()) {
            return $this;
        }

        $this->getCart()->removeProductFromCart($product);
        return $this;
    }

    public function getNumberOfProductsInCart(): int
    {
        if ($this->cart->isEmpty()) {
            return 0;
        }
        return count($this->getCart()->getProductCarts());
    }

    public function makePurchase()
    {
        $this->getCart()->setStatus('finished');

    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }
}
