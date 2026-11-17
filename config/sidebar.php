<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'fas-house',
        'route' => 'dashboard',
        'active' => 'dashboard*',
        'permission' => null, // Bebas diakses semua user terautentikasi
    ],
    [
        'title' => 'User Management',
        'icon' => 'fas-users',
        'active' => ['users*', 'roles*', 'permissions*'],
        'permission' => ['view-users', 'view-roles', 'view-permissions'], // Parent muncul jika punya salah satu
        'children' => [
            [
                'title' => 'Users',
                'icon' => 'fas-user',
                'route' => 'users.index',
                'active' => 'users*',
                'permission' => 'view-users',
            ],
            [
                'title' => 'Roles',
                'icon' => 'fas-shield',
                'route' => 'roles.index',
                'active' => 'roles*',
                'permission' => 'view-roles',
            ],
            [
                'title' => 'Permissions',
                'icon' => 'fas-key',
                'route' => 'permissions.index',
                'active' => 'permissions*',
                'permission' => 'view-permissions',
            ],
        ],
    ],
    [
        'title' => 'Toko Management',
        'icon' => 'fas-store',
        'route' => 'tokos.index',
        'active' => 'tokos*',
        'permission' => 'view-tokos',
    ],
    [
        'title' => 'Transaksi Management',
        'icon' => 'fas-money-bill',
        'active' => 'transaksis*',
        'permission' => ['view-transaksis', 'view-transaksi-kategoris'],
        'children' => [
            [
                'title' => 'Transaksis',
                'icon' => 'fas-money-bill',
                'route' => 'transaksis.index',
                'active' => 'transaksis*',
                'permission' => 'view-transaksis',
            ],
            [
                'title' => 'Transaksi Kategori',
                'icon' => 'fas-list',
                'route' => 'transaksi-kategoris.index',
                'active' => 'transaksi-kategoris*',
                'permission' => 'view-transaksi-kategoris',
            ],
        ],
    ],
    [
        'title' => 'Pembelian Management',
        'icon' => 'fas-cart-shopping',
        'active' => 'pembelians*',
        'permission' => ['view-pembelians',],
        'route' => 'pembelians.index',
    ],
    [
        'title' => 'Harga Emas',
        'icon' => 'fas-coins',
        'route' => 'harga-emas.index',
        'active' => 'harga-emas*',
        'permission' => 'view-harga-emas',
    ]
    
];