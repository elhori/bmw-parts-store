<?php

namespace App\Application\UseCases;

use App\Domain\Contract\IProductRepository;
use App\Domain\Entities\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductUseCase
{
    public function __construct(private IProductRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->repository->getPaginated($perPage);
    }

    public function getById(int $id): ?Product
    {
        return $this->repository->getById($id);
    }

    public function search(string $searchTerm) : LengthAwarePaginator
    {
        return $this->repository->search($searchTerm);
    }

    public function create(array $data): Product
    {
        return $this->repository->create(new Product(
            null,
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['category_id'] ?? null
        ));
    }

    public function update(int $id, array $data): Product
    {
        return $this->repository->update(new Product(
            $id,
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['category_id'] ?? null
        ));
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
