<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'type' => 'IN',
                'transaksi_kategori_id' => 1,
                'toko_id' => 1,
                'nominal' => 10000000000,
                'keterangan' => 'Initial transaction',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($data as $item) {
            Transaksi::create($item);
        }
    }
}
