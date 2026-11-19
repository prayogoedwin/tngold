<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignAdminTokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminTokoA = \App\Models\User::where('email', 'admintokoa@example.com')->first();
        $tokoA = \App\Models\Toko::where('name', 'Toko A')->first();
        if ($adminTokoA && $tokoA) {
            $adminTokoA->tokos()->sync([$tokoA->id]);
        }

        $adminTokoB = \App\Models\User::where('email', 'admintokob@example.com')->first();
        $tokoB = \App\Models\Toko::where('name', 'Toko B')->first();
        if ($adminTokoB && $tokoB) {
            $adminTokoB->tokos()->sync([$tokoB->id]);
        }

        $adminTokoC = \App\Models\User::where('email', 'admintokoc@example.com')->first();
        $tokoC = \App\Models\Toko::where('name', 'Toko C')->first();
        if ($adminTokoC && $tokoC) {
            $adminTokoC->tokos()->sync([$tokoC->id]);
        }
    }
}
