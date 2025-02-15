<?php

namespace App\Domain\Contract;

use App\Domain\Entities\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IProductRepository
{
    public function getAll(): array;
    public function getPaginated(int $perPage): LengthAwarePaginator;
    public function getById(int $id): ?Product;
    public function search(string $searchTerm): LengthAwarePaginator;
    public function create(Product $product): Product;
    public function update(Product $product): Product;
    public function delete(int $id): bool;
}
