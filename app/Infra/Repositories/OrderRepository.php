<?php

namespace App\Infra\Repositories;

use App\Domain\Contract\IOrderRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Infra\Models\Order as OrderModel;
use App\Domain\Entities\Order;

class OrderRepository implements IOrderRepository
{
    public function getAll(): array
    {
        return OrderModel::all()->map(fn($o) => $this->mapToEntity($o))->toArray();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return OrderModel::paginate($perPage)->through(fn($o) => $this->mapToEntity($o));
    }

    public function getById(int $id): ?Order
    {
        $order = OrderModel::find($id);
        return $order ? $this->mapToEntity($order) : null;
    }

    public function create(Order $order): Order
    {
        $model = OrderModel::create($order->toArray());
        return $this->mapToEntity($model);
    }

    public function delete(int $id): bool
    {
        return OrderModel::destroy($id) > 0;
    }

    private function mapToEntity(OrderModel $model): Order
    {
        return new Order(
            $model->id,
            $model->user_id,
            $model->status,
            $model->total_price
        );
    }
}
