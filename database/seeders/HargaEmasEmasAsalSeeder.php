<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HargaEmasEmasAsalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Emas Murni',
            ],
            [
                'name' => 'Perhiasan'
            ],
            [
                'name' => 'Emas Muda'
            ],
            [
                'name' => 'Rongsok'
            ],
            [
                'name' => 'Batangan'
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

        \App\Models\HargaEmas\HargaEmasEmasAsal::insert($data);
    }
}
