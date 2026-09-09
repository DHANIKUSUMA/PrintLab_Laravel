<?php

namespace Database\Seeders;

use App\Models\JenisKertas;
use Illuminate\Database\Seeder;

class JenisKertasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_kertas' => 'Kertas Lab',
                'harga' => 250,
            ],
            [
                'nama_kertas' => 'Kertas Sendiri',
                'harga' => 150,
            ],
        ];

        foreach ($data as $item) {
            JenisKertas::updateOrCreate(
                ['nama_kertas' => $item['nama_kertas']],
                ['harga' => $item['harga']]
            );
        }
    }
}
