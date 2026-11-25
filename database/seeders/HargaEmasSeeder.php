<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HargaEmasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'emas_asal' => 'Antam',
                'sumber' => 'Official',
                'kadar' => 24,
                'satuan' => 'gram',
                'jumlah' => 1,
                'harga_beli' => 1545000
            ],
            [
                'emas_asal' => 'UBS Gold',
                'sumber' => 'Official',
                'kadar' => 24,
                'satuan' => 'gram',
                'jumlah' => 1,
                'harga_beli' => 1512000
            ],
            [
                'emas_asal' => 'Galeri 24',
                'sumber' => 'Official',
                'kadar' => 24,
                'satuan' => 'gram',
                'jumlah' => 1,
                'harga_beli' => 1518000
            ],
            [
                'emas_asal' => 'Lotus Archi',
                'sumber' => 'Official',
                'kadar' => 24,
                'satuan' => 'gram',
                'jumlah' => 1,
                'harga_beli' => 1510000
            ],
            [
                'emas_asal' => 'King Halim',
                'sumber' => 'Official',
                'kadar' => 24,
                'satuan' => 'gram',
                'jumlah' => 1,
                'harga_beli' => 1508000
            ]
        ];

        // add created_by and updated_by fields
        $userId = 1;
        foreach ($data as &$hargaEmas) {
            $hargaEmas['created_by'] = $userId;
            $hargaEmas['updated_by'] = $userId;
        }

        \App\Models\HargaEmas\HargaEmas::insert($data);
    }
}
