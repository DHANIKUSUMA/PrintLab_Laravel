<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisKertasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_kertas')->insert([
            [
                'nama_kertas' => 'Kertas Lab',
                'harga' => 250,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kertas' => 'Kertas Sendiri',
                'harga' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
