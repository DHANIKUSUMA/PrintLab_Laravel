<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pesanan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Master Data Kertas
        $this->call([
            JenisKertasSeeder::class,
        ]);

        // 2. Buat Akun Admin Utama
        User::create([
            'name' => 'Admin PrintLab',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        // 3. Buat Akun Pengguna Contoh
        User::create([
            'name' => 'Pengguna Demo',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'pengguna',
            'status' => 'aktif',
        ]);

        // 4. Cetak 5 User Dummy dan 20 Pesanan Dummy Otomatis
        User::factory(5)->create();
        Pesanan::factory(20)->create();
    }
}
