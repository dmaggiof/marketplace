<?php

namespace Marketplace\Domain\Customer\Entity;

use Doctrine\ORM\Mapping as ORM;

class CustomerAddress
{
    private ?int $id = null;

    private ?string $address = null;

    private ?Customer $customer = null;

    private ?bool $default_address = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function isDefaultAddress(): ?bool
    {
        return $this->default_address;
    }

    public function setDefaultAddress(bool $default_address): static
    {
        $this->default_address = $default_address;

        return $this;
    }
}
