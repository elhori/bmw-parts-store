<?php

namespace App\Application\UseCases;

use App\Domain\Contract\IOrderRepository;
use App\Domain\Entities\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderUseCase
{
    public function __construct(private IOrderRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->repository->getPaginated($perPage);
    }

    public function getById(int $id): ?Order
    {
        return $this->repository->getById($id);
    }

    public function create(array $data): Order
    {
        return $this->repository->create(new Order(
            null,
            $data['user_id'],
            $data['status'],
            $data['total_price']
        ));
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
