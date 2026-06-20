<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        $guards = ['platform', 'supplier', 'distributor'];

        $permissionsByGuard = [
            'platform' => [
                'role.view', 'role.create', 'role.update', 'role.delete',
                'user.view', 'user.create', 'user.update', 'user.delete',
                'order.view', 'order.create', 'order.update', 'order.delete',
                'product.view', 'product.create', 'product.update', 'product.delete',
                'inventory.view', 'inventory.create', 'inventory.update',
                'refund.view', 'refund.update',
                'coupon.view', 'coupon.create', 'coupon.update', 'coupon.delete',
                'dashboard.view',
            ],
            'supplier' => [
                'order.view', 'order.update',
                'product.view', 'product.create', 'product.update',
                'inventory.view', 'inventory.update',
                'refund.view',
                'dashboard.view',
            ],
            'distributor' => [
                'order.view', 'order.create',
                'product.view',
                'inventory.view',
                'dashboard.view',
            ],
        ];

        foreach ($guards as $guard) {
            foreach ($permissionsByGuard[$guard] as $perm) {
                Permission::firstOrCreate([
                    'name' => $perm,
                    'guard_name' => $guard,
                ]);
            }
        }

        $rolesByGuard = [
            'platform' => [
                'platform-admin' => 'all',
                'platform-operator' => ['role.view', 'user.view', 'order.view', 'order.update', 'product.view', 'inventory.view', 'dashboard.view'],
                'platform-finance' => ['order.view', 'refund.view', 'refund.update', 'dashboard.view'],
            ],
            'supplier' => [
                'supplier-admin' => 'all',
                'supplier-operator' => ['order.view', 'order.update', 'product.view', 'product.update', 'inventory.view', 'dashboard.view'],
            ],
            'distributor' => [
                'distributor-admin' => 'all',
                'distributor-user' => ['order.view', 'order.create', 'product.view', 'inventory.view', 'dashboard.view'],
            ],
        ];

        foreach ($rolesByGuard as $guard => $roles) {
            foreach ($roles as $roleName => $perms) {
                $role = Role::firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => $guard,
                ]);

                if ($perms === 'all') {
                    $role->syncPermissions(Permission::where('guard_name', $guard)->get());
                } else {
                    $role->syncPermissions(
                        collect($perms)->map(fn ($p) => Permission::where('name', $p)->where('guard_name', $guard)->first())->filter()
                    );
                }
            }
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@platform.com'],
            [
                'name' => '平台管理员',
                'password' => Hash::make('password'),
                'guard_name' => 'platform',
                'is_active' => true,
            ]
        );
        $admin->assignRole('platform-admin');

        $supplierAdmin = User::firstOrCreate(
            ['email' => 'admin@supplier.com'],
            [
                'name' => '供应商管理员',
                'password' => Hash::make('password'),
                'guard_name' => 'supplier',
                'is_active' => true,
            ]
        );
        $supplierAdmin->assignRole('supplier-admin');

        $distributorAdmin = User::firstOrCreate(
            ['email' => 'admin@distributor.com'],
            [
                'name' => '经销商管理员',
                'password' => Hash::make('password'),
                'guard_name' => 'distributor',
                'is_active' => true,
            ]
        );
        $distributorAdmin->assignRole('distributor-admin');
    }
}
