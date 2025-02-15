<?php

namespace App\Domain\Contract;

use App\Domain\Entities\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IOrderRepository
{
    public function getAll(): array;

    public function getPaginated(int $perPage): LengthAwarePaginator;

    public function getById(int $id): ?Order;

    public function create(Order $order): Order;

    public function delete(int $id): bool;
}
