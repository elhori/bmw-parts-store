<?php

namespace App\Application\UseCases;

use App\Domain\Contract\IUserRepository;
use App\Domain\Entities\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserUseCase
{
    public function __construct(private IUserRepository $repository) {}

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->repository->getPaginated($perPage);
    }

    public function getById(int $id): ?User
    {
        return $this->repository->getById($id);
    }

    public function search(string $searchTerm): LengthAwarePaginator
    {
        return $this->repository->search($searchTerm);
    }

    public function create(array $data): User
    {
        return $this->repository->create(new User(
            null,
            $data['name'],
            $data['email'],
            bcrypt($data['password'])
        ));
    }

    public function update(int $id, array $data): User
    {
        return $this->repository->update(new User(
            $id,
            $data['name'],
            $data['email'],
            isset($data['password']) ? bcrypt($data['password']) : null
        ));
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function changePassword(int $id, string $pass): bool
    {
        return $this->repository->changePassword($id, $pass);
    }

    public function changeRoleToAdmin(int $user_id): bool
    {
        return $this->repository->changeRoleToAdmin($user_id);
    }

    public function changeRoleToManager(int $user_id): bool
    {
        return $this->repository->changeRoleToManager($user_id);
    }
}
