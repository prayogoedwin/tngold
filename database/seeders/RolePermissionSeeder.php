<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view-users',
            'show-users',
            'create-users',
            'edit-users',
            'download-users',
            'delete-users',
            'view-roles',
            'show-roles',
            'create-roles',
            'edit-roles',
            'download-roles',
            'delete-roles',
            'view-permissions',
            'show-permissions',
            'create-permissions',
            'edit-permissions',
            'download-permissions',
            'delete-permissions',

            //tokos
            'view-tokos',
            'show-tokos',
            'create-tokos',
            'edit-tokos',
            'download-tokos',
            'delete-tokos',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $ownerRole = Role::firstOrCreate(['name' => 'owner']);
        $adminTokoRole = Role::firstOrCreate(['name' => 'admin-toko']);

        $superAdminRole->permissions()->sync(Permission::all());
        $ownerRole->permissions()->sync(
            Permission::whereIn('name', [
                'view-users',
                'show-users',
                'create-users',
                'edit-users',
                'download-users',
                'delete-users',

                'view-tokos',
                'show-tokos',
                'edit-tokos',
                'download-tokos',
            ])->pluck('id')
        );

        $adminTokoRole->permissions()->sync(
            Permission::whereIn('name', [])->pluck('id')
        );

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        $superAdmin->roles()->sync([$superAdminRole->id]);

        $owner = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Owner',
                'password' => Hash::make('password'),
            ]
        );

        $owner->roles()->sync([$ownerRole->id]);

        $adminToko = User::firstOrCreate(
            ['email' => 'admintoko@example.com'],
            [
                'name' => 'Admin Toko User',
                'password' => Hash::make('password'),
            ]
        );

        $adminToko->roles()->sync([$adminTokoRole->id]);

        
    }
}
