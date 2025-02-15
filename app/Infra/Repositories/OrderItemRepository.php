<?php

namespace App\Infra\Repositories;

use App\Domain\Contract\IOrderItemRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\Entities\OrderItem;
use App\Infra\Models\OrderItem as OrderItemModel;

class OrderItemRepository implements IOrderItemRepository
{
    public function getAll(): array
    {
        return OrderItemModel::all()->map(fn($o) => $this->mapToEntity($o))->toArray();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return OrderItemModel::paginate($perPage)->through(fn($o) => $this->mapToEntity($o));
    }

    public function getById(int $id): ?OrderItem
    {
        $orderItem = OrderItemModel::find($id);
        return $orderItem ? $this->mapToEntity($orderItem) : null;
    }

    public function create(OrderItem $orderItem): OrderItem
    {
        $model = OrderItemModel::create($orderItem->toArray());
        return $this->mapToEntity($model);
    }

    public function update(OrderItem $orderItem): OrderItem
    {
        $model = OrderItemModel::findOrFail($orderItem->id);
        $model->update($orderItem->toArray());
        return $this->mapToEntity($model);
    }

    public function delete(int $id): bool
    {
        return OrderItemModel::destroy($id) > 0;
    }

    private function mapToEntity(OrderItemModel $model): OrderItem
    {
        return new OrderItem(
            $model->id,
            $model->order_id,
            $model->product_id,
            $model->quantity,
            $model->price
        );
    }
}
