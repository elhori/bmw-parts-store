<?php

namespace App\Domain\Contract;

use App\Domain\Entities\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ICategoryRepository
{
    public function getAll(): array;

    public function getPaginated(int $perPage): LengthAwarePaginator;

    public function getById(int $id): ?Category;

    public function search(string $searchTerm): LengthAwarePaginator;

    public function create(Category $category): Category;

    public function update(Category $category): Category;

    public function delete(int $id): bool;
}
