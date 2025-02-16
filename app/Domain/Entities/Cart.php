<?php

namespace App\Domain\Entities;

class Cart
{
    public int $userId;
    public array $items = [];

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->items = [];
    }

    public function getItem(int $productId): ?CartItem
    {
        foreach ($this->items as $item) {
            if ($item->getProductId() === $productId) {
                return $item;
            }
        }

        return null;
    }

    public function addItem(CartItem $cartItem): void
    {
        foreach ($this->items as $item) {
            if ($item->productId == $cartItem->productId) {
                $item->quantity += $cartItem->quantity;
                return;
            }
        }

        $this->items[] = $cartItem;
    }

    public function removeItem(int $productId): void
    {
        $this->items = array_filter($this->items, function ($item) use ($productId) {
            return $item->productId !== $productId;
        });
    }

    public function updateItemQuantity(int $productId, int $quantity): void
    {
        foreach ($this->items as $item) {
            if ($item->productId == $productId) {
                $item->quantity = $quantity;
                return;
            }
        }
    }
}
