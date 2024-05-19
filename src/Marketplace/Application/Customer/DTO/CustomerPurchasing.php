<?php

namespace Marketplace\Application\Customer\DTO;

class CustomerPurchasing
{
    public function __construct(public readonly int $userId){}

    public function getUserId(): int
    {
        return $this->userId;
    }
}