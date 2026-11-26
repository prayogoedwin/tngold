<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HargaEmasKadarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => '24K',
            ],
            [
                'name' => '23K'
            ],
            [
                'name' => '22K'
            ],
            [
                'name' => '18K'
            ],
            [
                'name' => '17K'
            ],
            [
                'name' => '16K'
            ],
            [
                'name' => '9K'
            ],
            [
                'name' => '8K'
            ],
            [
                'name' => '+-70%'
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

        // insert data into the database
        \App\Models\HargaEmas\HargaEmasKadar::insert($data);
    }
}
