<?php

namespace App\Domain\Contract;

use App\Domain\Entities\OrderItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IOrderItemRepository
{
    public function getAll(): array;
    public function getPaginated(int $perPage): LengthAwarePaginator;
    public function getById(int $id): ?OrderItem;
    public function create(OrderItem $orderItem): OrderItem;
    public function update(OrderItem $orderItem): OrderItem;
    public function delete(int $id): bool;
}
