<?php

namespace App\Domain\Entities;

use App\Domain\Contract\Imageable;

class Category implements Imageable
{
    public function __construct(
        public ?int   $id,
        public string $name
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getImageableType(): string
    {
        return \App\Infra\Models\Category::class;
    }
}
