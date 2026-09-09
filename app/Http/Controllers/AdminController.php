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
        $pesanan = Pesanan::with(['user', 'jenisKertas'])
        ->where('status', 'menunggu')
        ->latest()
        ->paginate(10);
    // Hitung statistik untuk Stat Cards
    $jumlah_verifikasi = Pesanan::where('status', 'menunggu')->count();
    $jumlah_selesai = Pesanan::where('status', 'disetujui')->whereDate('updated_at', today())->count();
    $jumlah_ditolak = Pesanan::where('status', 'ditolak')->whereDate('updated_at', today())->count();
    return view('admin.Verifikasi', compact(
        'pesanan',
        'jumlah_verifikasi',
        'jumlah_selesai',
        'jumlah_ditolak'
    ));
    }
    public function updateStatusVerifikasi(Request $request)
    {
        $request->validate([
            'kode_order' => 'required|exists:pesanan,kode_order',
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $pesanan = Pesanan::where('kode_order', $request->kode_order)->first();

        if ($pesanan) {
            $pesanan->status = $request->status;
            $pesanan->save();

            // Beri notifikasi
            return back()->with('success', "Pesanan " . $request->status . " berhasil!");
        } else {
            return back()->with('error', "Pesanan tidak ditemukan!");
        }
    }

}
