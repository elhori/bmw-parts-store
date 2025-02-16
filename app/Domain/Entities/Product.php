<?php

namespace App\Domain\Entities;

use App\Domain\Contract\Imageable;

class Product implements Imageable
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $description,
        public float $price,
        public int $stock,
        public ?int $categoryId = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->categoryId,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getImageableType(): string
    {
        return \App\Infra\Models\Product::class;
    }
}
