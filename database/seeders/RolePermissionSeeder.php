<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'products.create',
            'products.update',
            'products.approve',
            'categories.manage',
            'orders.manage',
            'payments.manage',
            'payouts.request',
            'payouts.approve',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
        $vendor = Role::firstOrCreate(['name' => 'vendor', 'guard_name' => 'api']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);

        $vendor->syncPermissions([
            'products.create',
            'products.update',
            'payouts.request',
        ]);

        $admin->syncPermissions($permissions);

        $user->syncPermissions([]);
    }
}
