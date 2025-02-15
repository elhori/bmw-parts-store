<?php

namespace App\Infra\Repositories;

use App\Domain\Contract\IProductRepository;
use App\Domain\Entities\Product;
use App\Infra\Models\Product as ProductModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements IProductRepository
{
    public function getAll(): array
    {
        return ProductModel::all()->map(fn($p) => $this->mapToEntity($p))->toArray();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return ProductModel::paginate($perPage)->through(fn($p) => $this->mapToEntity($p));
    }

    public function getById(int $id): ?Product
    {
        $product = ProductModel::find($id);
        return $product ? $this->mapToEntity($product) : null;
    }

    public function search(string $searchTerm): LengthAwarePaginator
    {
        return ProductModel::where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('description', 'like', '%' . $searchTerm . '%')
            ->paginate()->through(fn($p) => $this->mapToEntity($p));
    }

    public function create(Product $product): Product
    {
        $model = ProductModel::create($product->toArray());
        return $this->mapToEntity($model);
    }

    public function update(Product $product): Product
    {
        $model = ProductModel::findOrFail($product->id);
        $model->update($product->toArray());
        return $this->mapToEntity($model);
    }

    public function delete(int $id): bool
    {
        return ProductModel::destroy($id) > 0;
    }

    private function mapToEntity(ProductModel $model): Product
    {
        return new Product(
            $model->id,
            $model->name,
            $model->description,
            $model->price,
            $model->stock,
            $model->category_id
        );
    }
}
