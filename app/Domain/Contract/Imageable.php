<?php

namespace App\Domain\Contract;

interface Imageable
{
    public function getId(): int;
    public function getImageableType(): string;
}
