<?php

namespace Marketplace\Domain\Customer\Exceptions;

use Exception;
use Marketplace\Domain\Product\Entity\Product;

class InsufficientStockForProduct extends Exception
{
    public readonly string $product;

    /**
     * @param string $product
     */
    public function __construct(string $product)
    {
        $this->product = $product;
        parent::__construct();
    }
}