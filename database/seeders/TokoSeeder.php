<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Toko A',
                'address' => 'Jl. Contoh No. 1',
                'phone' => '081234567890',
            ],
            [
                'name' => 'Toko B',
                'address' => 'Jl. Contoh No. 2',
                'phone' => '081234567891',
            ],
            [
                'name' => 'Toko C',
                'address' => 'Jl. Contoh No. 3',
                'phone' => '081234567892',
            ],
        ];


        // add created_by and updated_by fields
        $userId = 1;
        foreach ($data as &$toko) {
            $toko['created_by'] = $userId;
            $toko['updated_by'] = $userId;
        }

        \App\Models\Toko::insert($data);
    }
}
