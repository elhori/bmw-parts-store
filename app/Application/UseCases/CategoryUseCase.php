<?php

namespace App\Application\UseCases;

use App\Domain\Contract\ICategoryRepository;
use App\Domain\Contract\IImageRepository;
use App\Domain\Entities\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

class CategoryUseCase
{
    public function __construct(
        private ICategoryRepository $repository,
        private IImageRepository $imageRepository) {}

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->repository->getPaginated($perPage);
    }

    public function getById(int $id): ?Category
    {
        return $this->repository->getById($id);
    }

    public function search(string $searchTerm) : LengthAwarePaginator
    {
        return $this->repository->search($searchTerm);
    }

    public function create(array $data, ?UploadedFile $image = null): Category
    {
        $category = $this->repository->create(new Category(null, $data['name']));

        if ($image) {
            $this->imageRepository->uploadImage($image, $category);
        }

        return $category;
    }

    public function update(array $data, ?UploadedFile $image = null): Category
    {
        $category = $this->repository->update(new Category($data['id'], $data['name']));

        if ($image) {
            $this->imageRepository->uploadImage($image, $category);
        }

        return $category;
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
