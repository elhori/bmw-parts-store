<?php

namespace App\Domain\Entities;

class OrderItem
{
    public function __construct(
        public ?int  $id,
        public int   $orderId,
        public int   $productId,
        public int   $quantity,
        public float $price
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->orderId,
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}
