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
                'emas_asal_id' => 1,
                'sumber' => 'Official',
                'kadar' => 24,
                'satuan' => 'gram',
                'jumlah' => 1,
                'harga_beli' => 1545000
            ],
            [
                'emas_asal_id' => 2,
                'sumber' => 'Official',
                'kadar' => 24,
                'satuan' => 'gram',
                'jumlah' => 1,
                'harga_beli' => 1512000
            ],
            [
                'emas_asal_id' => 3,
                'sumber' => 'Official',
                'kadar' => 24,
                'satuan' => 'gram',
                'jumlah' => 1,
                'harga_beli' => 1518000
            ],
            [
                'emas_asal_id' => 4,
                'sumber' => 'Official',
                'kadar' => 24,
                'satuan' => 'gram',
                'jumlah' => 1,
                'harga_beli' => 1510000
            ],
            [
                'emas_asal_id' => 5,
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
            $hargaEmas['created_at'] = now();
            $hargaEmas['updated_by'] = $userId;
            $hargaEmas['updated_at'] = now();
        }

        foreach ($data as $hargaEmas) {
            \App\Models\HargaEmas\HargaEmas::create($hargaEmas);
        }
    }
}
