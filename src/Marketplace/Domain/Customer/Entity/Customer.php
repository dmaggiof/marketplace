<?php

namespace Marketplace\Domain\Customer\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;
use Marketplace\Domain\Cart\Exceptions\CantEditAddressOnFinishedCart;
use Marketplace\Domain\Cart\Exceptions\CartIsEmpty;
use Marketplace\Domain\Cart\Entity\Cart;
use Marketplace\Domain\Cart\Exceptions\CantAddProductsToFinishedCart;
use Marketplace\Domain\Cart\Exceptions\CantHaveMoreThanThreeProductsInCart;
use Marketplace\Domain\Customer\Exceptions\CustomerHasNoAddressConfigured;
use Marketplace\Domain\Order\Entity\Order;
use Marketplace\Domain\Product\Entity\Product;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Customer implements UserInterface, PasswordAuthenticatedUserInterface
{
    private ?int $id = null;

    private ?string $name = null;

    private ?string $email = null;

    private ?string $password = null;

    private Collection $orders;

    private Collection $cart;

    /**
     * @var Collection<int, CustomerAddress>
     */
    private Collection $customerAddresses;
    private string $plainPassword;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->cart = new ArrayCollection();
        $this->customerAddresses = new ArrayCollection();
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

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
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
        return $this->cart->last();
    }

    public function getPendingCart(): Cart
    {
        $finder = function($cart){
            return $cart->getStatus() === 'pending';
        };
        $cartsInPendingStatus = $this->cart->filter($finder);

        if ($cartsInPendingStatus->isEmpty()) {
            $cart = new Cart();
            $cart->setStatus(Cart::PENDING_CART);
            $cart->setCustomerId($this);
            $this->cart->add($cart);
        } else {
//            dd($cartsInPendingStatus->first());
            $cart = $cartsInPendingStatus->first();
        }
        return $cart;
    }

    /**
     * @throws Exception
     */
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
                throw new Exception("Ya tienes un carrito activo");
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

    /**
     * @throws CantHaveMoreThanThreeProductsInCart
     * @throws CantAddProductsToFinishedCart
     * @throws Exception
     */
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

    /**
     * @throws CustomerHasNoAddressConfigured
     * @throws CantEditAddressOnFinishedCart
     */
    public function makePurchase(): void
    {
        if (!$this->getCustomerDefaultAddress()) {
            throw new CustomerHasNoAddressConfigured;
        }
        if ($this->getPendingCart()->getProductCarts()->isEmpty()) {
            throw new CartIsEmpty;
        }
        $this->getPendingCart()->markAsCompleted();

    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getCustomerAddresses(): Collection
    {
        return $this->customerAddresses;
    }

    public function getCustomerDefaultAddress(): ?CustomerAddress
    {
        $finder = function($address){
            return $address->isDefaultAddress() === true;
        };
        $addresses = $this->customerAddresses->filter($finder);
        if (empty($addresses)) {
            return null;
        } else {
            return $addresses[0];
        }
    }

    public function setAddress(CustomerAddress $customerAddress): static
    {
        if ($this->customerAddresses->contains($customerAddress)) {
            $this->customerAddresses->remove($customerAddress);
        }
        $this->customerAddresses->forAll(function($key, $address) {
            if ($address->isDefaultAddress() === true) {
                $address->setDefaultAddress(false);
                return true;
            }
            return true;
        });
        $customerAddress->setDefaultAddress(true);
        $customerAddress->setCustomer($this);
        $this->customerAddresses->add($customerAddress);


        return $this;
    }

    public function addCustomerAddress(CustomerAddress $customerAddress): static
    {
        if (!$this->customerAddresses->contains($customerAddress)) {
            $this->customerAddresses->add($customerAddress);
            $customerAddress->setCustomer($this);
        }

        return $this;
    }

    public function removeCustomerAddress(CustomerAddress $customerAddress): static
    {
        $this->customerAddresses->removeElement($customerAddress);

        return $this;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}
