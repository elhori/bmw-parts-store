<?php

namespace App\Domain\Entities;

class User
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public ?string $password = null,
        public array $roles = [],
        public array $permissions = []
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'roles' => $this->roles,
            'permissions' => $this->permissions,
        ];
    }
}
