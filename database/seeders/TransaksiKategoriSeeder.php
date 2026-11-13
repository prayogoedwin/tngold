<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransaksiKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Modal',
                'type' => 'IN',
            ],
            [
                'name' => 'Terima Pembayaran',
                'type' => 'IN',
            ],
            [
                'name' => 'Terima Pengembalian',
                'type' => 'IN',
            ],
            [
                'name' => 'Terima Pembelian',
                'type' => 'OUT',
            ],
            [
                'name' => 'Terima Kasbon',
                'type' => 'OUT',
            ],
        ];

        // add created_by and updated_by fields
        $userId = 1;
        foreach ($data as &$d) {
            $d['created_by'] = $userId;
            $d['updated_by'] = $userId;
        }

        \App\Models\TransaksiKategori::insert($data);
    }
}
