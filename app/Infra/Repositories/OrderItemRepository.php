<?php

namespace App\Infra\Repositories;

use App\Domain\Contract\IOrderItemRepository;
use App\Infra\Models\Product;
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

    public function create(OrderItem $orderItem): string
    {
        $product = Product::findOrFail($orderItem->productId);

        if ($orderItem->quantity > $product->stock) {
            return "Not enough stock for product {$product->name}. Available stock: {$product->stock}";
        }

        $model = OrderItemModel::create($orderItem->toArray());

        $product->decrement('stock', $orderItem->quantity);

        return "Successfully created order item {$model->id}";
    }

    public function update(OrderItem $orderItem): string
    {
        $product = Product::findOrFail($orderItem->productId);
        $existingOrderItem = OrderItemModel::findOrFail($orderItem->id);

        if ($orderItem->quantity > $product->stock + $existingOrderItem->quantity) {
            return "Not enough stock for product {$product->name}. Available stock: {$product->stock}";
        }

        $quantityDifference = $orderItem->quantity - $existingOrderItem->quantity;

        if ($quantityDifference > 0) {
            $product->decrement('stock', $quantityDifference);
        } elseif ($quantityDifference < 0) {
            $product->increment('stock', abs($quantityDifference));
        }

        $existingOrderItem->update($orderItem->toArray());

        return "Successfully updated order item {$existingOrderItem->id}";
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
