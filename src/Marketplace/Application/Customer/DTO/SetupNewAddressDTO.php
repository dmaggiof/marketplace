<?php

namespace Marketplace\Application\Customer\DTO;

class SetupNewAddressDTO
{
    public function __construct(public readonly int $userId, public readonly string $address){}

}