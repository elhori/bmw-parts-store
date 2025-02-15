<?php

namespace Database\Seeders;

use App\Infra\Models\Permission;
use App\Infra\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء الأدوار
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $userRole = Role::firstOrCreate(['name' => 'basic_user']);

        // إنشاء الصلاحيات
        $permissions = [
            // المنتجات (Products)
            'view-products',
            'create-products',
            'edit-products',
            'delete-products',

            // الفئات (Categories)
            'view-categories',
            'create-categories',
            'edit-categories',
            'delete-categories',

            // الطلبات (Orders)
            'view-orders',
            'create-orders',
            'update-orders',
            'delete-orders',

            // عناصر الطلبات (Order Items)
            'view-order-items',
            'manage-order-items', // تعديل العناصر داخل الطلبات

            'view-users',
            'create-users',
            'edit-users',
            'delete-users'
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::firstOrCreate(['name' => $perm]);

            $adminRole->permissions()->syncWithoutDetaching($permission);
        }

        $managerPermissions = [
            'view-products',
            'edit-products',
            'view-categories',
            'view-orders',
            'update-orders',
            'manage-order-items',
        ];

        foreach ($managerPermissions as $perm) {
            $permission = Permission::where('name', $perm)->first();
            if ($permission) {
                $managerRole->permissions()->syncWithoutDetaching($permission);
            }
        }

        $userPermissions = [
            'view-products',
            'view-categories',
            'view-orders',
            'create-orders',
        ];

        foreach ($userPermissions as $perm) {
            $permission = Permission::where('name', $perm)->first();
            if ($permission) {
                $userRole->permissions()->syncWithoutDetaching($permission);
            }
        }
    }
}
