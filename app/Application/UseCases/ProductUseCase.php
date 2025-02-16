<?php

namespace App\Application\UseCases;

use App\Domain\Contract\IImageRepository;
use App\Domain\Contract\IProductRepository;
use App\Domain\Entities\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;

class ProductUseCase
{
    public function __construct(
        private IProductRepository $repository,
        private IImageRepository $imageRepository) {}

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->repository->getPaginated($perPage);
    }

    public function getById(int $id): ?Product
    {
        return $this->repository->getById($id);
    }

    public function search(string $searchTerm) : LengthAwarePaginator
    {
        return $this->repository->search($searchTerm);
    }

    public function create(array $data, ?UploadedFile $image = null): Product
    {
        $product = $this->repository->create(new Product(
            null,
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['category_id'] ?? null
        ));

        if ($image) {
            $this->imageRepository->uploadImage($image, $product);
        }

        return $product;
    }

    public function update(array $data, ?UploadedFile $image = null): Product
    {
        $product = $this->repository->update(new Product(
            $data['id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['category_id'] ?? null
        ));

        if ($image) {
            $this->imageRepository->uploadImage($image, $product);
        }

        return $product;
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
