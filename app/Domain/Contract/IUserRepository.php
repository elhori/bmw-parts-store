<?php

namespace App\Domain\Contract;

use App\Domain\Entities\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface IUserRepository
{
    public function getAll(): array;
    public function getPaginated(int $perPage): LengthAwarePaginator;
    public function getById(int $id): ?User;
    public function search(string $searchTerm): LengthAwarePaginator;
    public function create(User $user): User;
    public function update(User $user): User;
    public function delete(int $id): bool;
    public function changePassword(int $user_id, string $password): bool;
    public function changeRoleToAdmin(int $user_id): bool;
    public function changeRoleToManager(int $user_id): bool;
}
