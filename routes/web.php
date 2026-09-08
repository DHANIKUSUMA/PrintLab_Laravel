<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/KelolaPesanan', [AdminController::class, 'KelolaPesanan'])->name('admin.kelola-pesanan');
    Route::get('/admin/Verifikasi', [AdminController::class, 'Verifikasi'])->name('admin.verifikasi');
});


Route::middleware(['auth', 'role:pengguna'])->group(function () {
    Route::get('/pesanan/create', [PesananController::class, 'create'])->name('pesanan.create');
    Route::post('/pesanan', [PesananController::class, 'store'])->name('pesanan.store');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // Jika admin mengakses /dashboard, redirect ke dashboard admin
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    $userId = $user->id_user;

    // Ambil daftar pesanan milik user ini
    $pesanan = Pesanan::where('id_user', $userId)->latest()->get();

    // Hitung jumlah pesanan yang disetujui / selesai
    $jumlah_selesai = $pesanan->whereIn('status', ['disetujui', 'selesai'])->count();

    return view('dashboard', compact('pesanan', 'jumlah_selesai'));
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

