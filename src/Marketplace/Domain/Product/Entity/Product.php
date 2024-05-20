<?php

namespace Marketplace\Domain\Product\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Version;
use Marketplace\Domain\Supplier\Entity\Supplier;
use Marketplace\Infrastructure\Product\Repository\ProductRepository;

class Product
{
    private ?int $id = null;

    private ?string $name = null;

    private ?int $price = null;

    private ?string $description = null;

    private ?int $stock_quantity = null;

    private ?int $version = null;

    private ?Supplier $supplier_id = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStockQuantity(): ?int
    {
        return $this->stock_quantity;
    }

    public function setStockQuantity(int $stock_quantity): static
    {
        $this->stock_quantity = $stock_quantity;

        return $this;
    }

    public function getSupplierId(): ?Supplier
    {
        return $this->supplier_id;
    }

    public function setSupplierId(?Supplier $supplier_id): static
    {
        $this->supplier_id = $supplier_id;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(?int $version): void
    {
        $this->version = $version;
    }
}
