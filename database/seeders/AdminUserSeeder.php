<?php

namespace Database\Seeders;

use App\Infra\Models\Role;
use App\Infra\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('admin2025'),
        ]);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
