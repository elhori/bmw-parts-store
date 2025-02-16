<?php

namespace App\Domain\Entities;

class CartItem
{
    public int $productId;
    public int $quantity;

    public function __construct(int $productId, int $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }
}
