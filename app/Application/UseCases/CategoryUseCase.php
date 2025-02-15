<?php

namespace App\Application\UseCases;

use App\Domain\Contract\ICategoryRepository;
use App\Domain\Entities\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryUseCase
{
    public function __construct(private ICategoryRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->repository->getPaginated($perPage);
    }

    public function getById(int $id): ?Category
    {
        return $this->repository->getById($id);
    }

    public function search(string $searchTerm) : LengthAwarePaginator
    {
        return $this->repository->search($searchTerm);
    }

    public function create(array $data): Category
    {
        return $this->repository->create(new Category(null, $data['name']));
    }

    public function update(int $id, array $data): Category
    {
        return $this->repository->update(new Category($id, $data['name']));
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
