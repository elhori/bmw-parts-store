<?php

namespace App\Infra\Repositories;

use App\Domain\Contract\ICategoryRepository;
use App\Domain\Entities\Category;
use App\Infra\Models\Category as CategoryModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryRepository implements ICategoryRepository
{
    public function getAll(): array
    {
        return CategoryModel::all()->map(fn($c) => $this->mapToEntity($c))->toArray();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return CategoryModel::paginate($perPage)->through(fn($c) => $this->mapToEntity($c));
    }

    public function getById(int $id): ?Category
    {
        $category = CategoryModel::find($id);
        return $category ? $this->mapToEntity($category) : null;
    }

    public function search(string $searchTerm): LengthAwarePaginator
    {
        return CategoryModel::where('name', 'like', '%' . $searchTerm . '%')
            ->paginate()->through(fn($c) => $this->mapToEntity($c));
    }

    public function create(Category $category): Category
    {
        $model = CategoryModel::create($category->toArray());
        return $this->mapToEntity($model);
    }

    public function update(Category $category): Category
    {
        $model = CategoryModel::findOrFail($category->id);
        $model->update($category->toArray());
        return $this->mapToEntity($model);
    }

    public function delete(int $id): bool
    {
        return CategoryModel::destroy($id) > 0;
    }

    private function mapToEntity(CategoryModel $model): Category
    {
        return new Category(
            $model->id,
            $model->name
        );
    }
}
