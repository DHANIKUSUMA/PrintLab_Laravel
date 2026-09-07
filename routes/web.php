<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PesananController;
use Illuminate\Support\Facades\Route;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.register');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
});


Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::get('/pesanan/create', [PesananController::class, 'create'])->name('pesanan.create');
    Route::post('/pesanan', [PesananController::class, 'store'])->name('pesanan.store');
});

Route::get('/dashboard', function () {
    $userId = Auth::user()->id_user;

    // Ambil daftar pesanan milik user ini
    $pesanan = Pesanan::where('id_user', $userId)->latest()->get();

    // Hitung jumlah pesanan yang disetujui / selesai
    $jumlah_selesai = $pesanan->whereIn('status', ['disetujui', 'selesai'])->count();

    return view('dashboard', compact('pesanan', 'jumlah_selesai'));
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
