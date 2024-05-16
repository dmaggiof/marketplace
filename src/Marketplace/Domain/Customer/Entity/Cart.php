<?php

namespace Marketplace\Domain\Customer\Entity;

use Marketplace\Domain\ProductCart\Entity\ProductCart;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Marketplace\Domain\Product\Entity\Product;
use Marketplace\Infrastructure\Customer\Infrastructure\Repository\CartRepository;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToOne(inversedBy: 'cart')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer_id = null;

    /**
     * @var Collection<int, ProductCart>
     */
    #[ORM\OneToMany(targetEntity: ProductCart::class, mappedBy: 'cart', cascade:['persist','remove'])]
    private Collection $productCarts;

    #[ORM\Column(length: 25)]
    private ?string $status = null;

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
