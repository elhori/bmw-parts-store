<?php

namespace App\Application\UseCases;

use App\Domain\Contract\IOrderItemRepository;
use App\Domain\Entities\OrderItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderItemUseCase
{
    public function __construct(private IOrderItemRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->repository->getPaginated($perPage);
    }

    public function getById(int $id): ?OrderItem
    {
        return $this->repository->getById($id);
    }

    public function create(array $data): string
    {
        return $this->repository->create(new OrderItem(
            null,
            $data['order_id'],
            $data['product_id'],
            $data['quantity'],
            $data['price']
        ));
    }

    public function update(int $id, array $data): string
    {
        return $this->repository->update(new OrderItem(
            $id,
            $data['order_id'],
            $data['product_id'],
            $data['quantity'],
            $data['price']
        ));
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
