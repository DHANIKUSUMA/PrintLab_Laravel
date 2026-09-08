<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Ambil semua pesanan untuk dashboard admin
        $pesanan = Pesanan::with(['user', 'jenisKertas'])->latest()->get();

        // Statistik
        $jumlah_print = Pesanan::whereDate('created_at', today())->count();
        $pendapatan_harian = Pesanan::whereDate('created_at', today())
            ->whereIn('status', ['disetujui', 'selesai'])
            ->sum('total_biaya');
        $total_pengeluaran = 0; // Default jika belum ada fitur pengeluaran
        $saldo_kas = Pesanan::whereIn('status', ['disetujui', 'selesai'])->sum('total_biaya') - $total_pengeluaran;

        return view('admin.dashboardAdmin', compact(
            'pesanan',
            'jumlah_print',
            'pendapatan_harian',
            'total_pengeluaran',
            'saldo_kas'
        ));
    }
    public function KelolaPesanan()
    {
        $pesanan = Pesanan::with(['user', 'jenisKertas'])->latest()->paginate(10);
        return view('admin.KelolaPesanan', compact('pesanan'));
    }
    public function Verifikasi()
    {
        $pesanan = Pesanan::with(['user', 'jenisKertas'])->latest()->paginate(10);
        return view('admin.Verifikasi', compact('pesanan'));
    }
}
