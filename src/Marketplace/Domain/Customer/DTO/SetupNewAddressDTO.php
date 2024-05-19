<?php

namespace Marketplace\Domain\Customer\DTO;

class SetupNewAddressDTO
{
    public function __construct(public readonly int $userId, public readonly string $address){}

}