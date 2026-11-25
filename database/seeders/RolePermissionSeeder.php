<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Toko;
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

            //transaksis
            'view-transaksis',
            'show-transaksis',
            'create-transaksis',
            'edit-transaksis',
            'download-transaksis',
            'delete-transaksis',

            //transaksi kategoris
            'view-transaksi-kategoris',
            'show-transaksi-kategoris',
            'create-transaksi-kategoris',
            'edit-transaksi-kategoris',
            'download-transaksi-kategoris',
            'delete-transaksi-kategoris',

            //harga emas
            'view-harga-emas',
            'show-harga-emas',
            'create-harga-emas',
            'edit-harga-emas',
            'download-harga-emas',
            'delete-harga-emas',

            //harga emas history
            'view-harga-emas-history',
            'show-harga-emas-history',
            'create-harga-emas-history',
            'edit-harga-emas-history',
            'download-harga-emas-history',
            'delete-harga-emas-history',

            //pembelians
            'view-pembelians',
            'show-pembelians',
            'create-pembelians',
            'edit-pembelians',
            'download-pembelians',
            'delete-pembelians',

            //pembelian details
            'view-pembelian-details',
            'show-pembelian-details',
            'create-pembelian-details',
            'edit-pembelian-details',
            'download-pembelian-details',
            'delete-pembelian-details',

            //app versions
            'view-app-versions',
            'show-app-versions',
            'create-app-versions',
            'edit-app-versions',
            'download-app-versions',
            'delete-app-versions',
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

                'create-transaksi-kategoris',
                'view-transaksi-kategoris',
                'show-transaksi-kategoris',
                'edit-transaksi-kategoris',
                'download-transaksi-kategoris',
                'delete-transaksi-kategoris',

                'create-transaksis',
                'view-transaksis',
                'show-transaksis',
                'edit-transaksis',
                'download-transaksis',
                'delete-transaksis',

                'create-harga-emas',
                'view-harga-emas',
                'show-harga-emas',
                'edit-harga-emas',
                'download-harga-emas',
                'delete-harga-emas',

                'create-harga-emas-history',
                'view-harga-emas-history',
                'show-harga-emas-history',
                'edit-harga-emas-history',
                'download-harga-emas-history',
                'delete-harga-emas-history',

                'create-pembelians',
                'view-pembelians',
                'show-pembelians',
                'edit-pembelians',
                'download-pembelians',
                'delete-pembelians',

                'create-pembelian-details',
                'view-pembelian-details',
                'show-pembelian-details',
                'edit-pembelian-details',
                'download-pembelian-details',
                'delete-pembelian-details',

                'view-app-versions',
                'show-app-versions',
                'create-app-versions',
                'edit-app-versions',
                'download-app-versions',
                'delete-app-versions',
            ])->pluck('id')
        );

        $adminTokoRole->permissions()->sync(
            Permission::whereIn('name', [
                'view-pembelians',
                'show-pembelians',
                'create-pembelians',
                'edit-pembelians',
                'download-pembelians',
                'delete-pembelians',
            ])->pluck('id')
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
                'name' => 'Admin Toko',
                'password' => Hash::make('password'),
            ]
        );

        $adminToko->roles()->sync([$adminTokoRole->id]);

        $adminTokoA = User::firstOrCreate(
            ['email' => 'admintokoa@example.com'],
            [
                'name' => 'Admin Toko A',
                'password' => Hash::make('password'),
            ]
        );
        $adminTokoA->roles()->sync([$adminTokoRole->id]);
        

        $adminTokoB = User::firstOrCreate(
            ['email' => 'admintokob@example.com'],
            [
                'name' => 'Admin Toko B',
                'password' => Hash::make('password'),
            ]
        );
        $adminTokoB->roles()->sync([$adminTokoRole->id]);
        

        $adminTokoC = User::firstOrCreate(
            ['email' => 'admintokoc@example.com'],
            [
                'name' => 'Admin Toko C',
                'password' => Hash::make('password'),
            ]
        );
        $adminTokoC->roles()->sync([$adminTokoRole->id]);
        
    }
}
