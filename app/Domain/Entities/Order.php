<?php

namespace App\Domain\Entities;

class Order
{
    public function __construct(
        public ?int   $id,
        public int    $userId,
        public string $status,
        public float  $totalPrice,
        public array  $items = []
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'status' => $this->status,
            'total_price' => $this->totalPrice,
            'items' => array_map(fn($item) => $item->toArray(), $this->items),
        ];
    }
}
