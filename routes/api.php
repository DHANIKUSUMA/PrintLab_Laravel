<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\LaporanApiController;
use App\Http\Controllers\Api\PesananApiController;
use App\Http\Controllers\Api\PengeluaranApiController;
use App\Http\Controllers\Api\JenisKertasApiController;

/*
|--------------------------------------------------------------------------
| API Routes - PrintLab REST API
|--------------------------------------------------------------------------
|
| Semua endpoint API terdaftar di sini dengan prefix default `/api`.
|
*/

// ==========================================
// 1. AUTHENTICATION & USERS
// ==========================================
Route::post('/login', [AuthApiController::class, 'login']);
Route::get('/users', [AuthApiController::class, 'users']);

// ==========================================
// 2. LAPORAN KEUANGAN & OPERASIONAL
// ==========================================
Route::get('/laporan', [LaporanApiController::class, 'index']);

// ==========================================
// 3. MASTER JENIS KERTAS
// ==========================================
Route::get('/jenis-kertas', [JenisKertasApiController::class, 'index']);
Route::get('/jenis-kertas/{id}', [JenisKertasApiController::class, 'show']);

// ==========================================
// 4. PESANAN (ORDERS)
// ==========================================
Route::get('/pesanan', [PesananApiController::class, 'index']);
Route::get('/pesanan/{id}', [PesananApiController::class, 'show']);
Route::post('/pesanan', [PesananApiController::class, 'store']);
Route::post('/pesanan/{kode}/status', [PesananApiController::class, 'updateStatus']);

// ==========================================
// 5. PENGELUARAN (EXPENSES)
// ==========================================
Route::get('/pengeluaran', [PengeluaranApiController::class, 'index']);
Route::get('/pengeluaran/{id}', [PengeluaranApiController::class, 'show']);
Route::post('/pengeluaran', [PengeluaranApiController::class, 'store']);
