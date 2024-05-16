<?php

namespace Marketplace\Domain\Customer\Entity;

use Marketplace\Domain\Customer\Exceptions\CantHaveMoreThanThreeProductsInCart;
use Marketplace\Domain\Order\Entity\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Marketplace\Domain\Product\Entity\Product;
use Marketplace\Domain\ProductCart\Entity\ProductCart;
use Marketplace\Infrastructure\Customer\Infrastructure\Repository\CustomerRepository;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'customer_id', orphanRemoval: true)]
    private Collection $orders;

    /**
     * @var Collection<int, Cart>
     */
    #[ORM\OneToMany(targetEntity: Cart::class, mappedBy: 'customer_id', cascade:['persist','remove'], orphanRemoval: true)]
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
        if (!$this->cart->contains($cart)) {
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
            $this->cart[0] = new Cart($this);
            $this->cart[0]->setStatus('pending');
        }
        if ($this->getNumberOfProductsInCart() === 3) {
            throw new CantHaveMoreThanThreeProductsInCart();
        }
        $this->getCart()->addProductToCart($product, $quantity);
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
}
