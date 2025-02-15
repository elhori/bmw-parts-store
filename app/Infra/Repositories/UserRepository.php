<?php

namespace App\Infra\Repositories;

use App\Domain\Contract\IUserRepository;
use App\Infra\Models\Role;
use App\Infra\Models\User as UserModel;
use App\Domain\Entities\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{
    public function getAll(): array
    {
        return UserModel::all()->map(fn($u) => $this->mapToEntity($u))->toArray();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return UserModel::paginate($perPage)->through(fn($p) => $this->mapToEntity($p));
    }

    public function getById(int $id): ?User
    {
        $user = UserModel::find($id);
        return $user ? $this->mapToEntity($user) : null;
    }

    public function search(string $searchTerm): LengthAwarePaginator
    {
        return UserModel::where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('email', 'like', '%' . $searchTerm . '%')
            ->paginate(10)
            ->through(fn($u) => $this->mapToEntity($u));
    }

    public function create(User $user): User
    {
        $model = UserModel::create($user->toArray());

        $basicUserRole = Role::where('name', 'basic_user')->first();

        if ($basicUserRole) {
            $model->roles()->attach($basicUserRole->id);
        }

        return $this->mapToEntity($model);
    }

    public function update(User $user): User
    {
        $model = UserModel::findOrFail($user->id);
        $model->update($user->toArray());
        return $this->mapToEntity($model);
    }

    public function delete(int $id): bool
    {
        DB::table('role_user')->where('user_id', $id)->delete();

        return UserModel::destroy($id) > 0;
    }

    public function changePassword(int $user_id, string $password): bool
    {
        $user = UserModel::findOrFail($user_id);

        if(!$user)
            return false;

        $user->password = Hash::make($password);

        return $user->save();
    }

    private function mapToEntity(UserModel $model): User
    {
        return new User(
            $model->id,
            $model->name,
            $model->email,
            $model->password
        );
    }

    public function changeRoleToAdmin(int $user_id): bool
    {
        return DB::transaction(function () use ($user_id) {
            $user = UserModel::findOrFail($user_id);
            $adminRoleId = Role::where('name', 'admin')->value('id');

            $user->roles()->sync([$adminRoleId]);

            return true;
        });
    }

    public function changeRoleToManager(int $user_id): bool
    {
        return DB::transaction(function () use ($user_id) {
            $user = UserModel::findOrFail($user_id);
            $managerRoleId = Role::where('name', 'manager')->value('id');

            $user->roles()->sync([$managerRoleId]);

            return true;
        });
    }
}
