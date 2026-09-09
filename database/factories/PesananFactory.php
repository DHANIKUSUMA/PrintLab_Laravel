<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\JenisKertas;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PesananFactory extends Factory
{
    public function definition(): array
    {
        // Ambil jenis kertas acak untuk hitung harga
        $kertas = JenisKertas::inRandomOrder()->first() ?? JenisKertas::create(['nama_kertas' => 'A4 80gr', 'harga' => 500]);
        $jumlahLembar = fake()->numberBetween(5, 100);

        return [
            // Pilih user acak dari database
            'id_user' => User::where('role', 'pengguna')->inRandomOrder()->first()?->id_user ?? User::factory(),
            'id_jenis_kertas' => $kertas->id_jenis_kertas,
            'jumlah_lembar' => $jumlahLembar,
            'total_biaya' => $jumlahLembar * $kertas->harga,
            'status' => fake()->randomElement(['menunggu', 'disetujui', 'ditolak']),
            'metode_pembayaran' => fake()->randomElement(['Transfer Bank (BCA)', 'QRIS', 'Tunai']),
            'bukti_pembayaran' => 'bukti_pembayaran/dummy.jpg',
            'kode_order' => 'ORD-' . strtoupper(Str::random(6)),
        ];
    }
}
