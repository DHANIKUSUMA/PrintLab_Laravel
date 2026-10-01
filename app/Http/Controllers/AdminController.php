<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Ambil pesanan untuk dashboard admin (paginated)
        $pesanan = Pesanan::with(['user', 'jenisKertas'])->latest()->paginate(10);

        // Konsolidasi statistik pesanan menjadi 1 query agregasi
        $today = today()->toDateString();
        $pesananStats = Pesanan::selectRaw("
            COUNT(CASE WHEN DATE(created_at) = ? THEN 1 END) as jumlah_print,
            COALESCE(SUM(CASE WHEN DATE(created_at) = ? AND status IN ('disetujui', 'selesai') THEN total_biaya ELSE 0 END), 0) as pendapatan_harian,
            COALESCE(SUM(CASE WHEN status IN ('disetujui', 'selesai') THEN total_biaya ELSE 0 END), 0) as total_pendapatan
        ", [$today, $today])->first();

        $jumlah_print = (int) ($pesananStats->jumlah_print ?? 0);
        $pendapatan_harian = (float) ($pesananStats->pendapatan_harian ?? 0);
        $total_pengeluaran = (float) (Pengeluaran::sum('jumlah') ?? 0);
        $saldo_kas = (float) (($pesananStats->total_pendapatan ?? 0) - $total_pengeluaran);

        return view('admin.dashboardAdmin', compact(
            'pesanan',
            'jumlah_print',
            'pendapatan_harian',
            'total_pengeluaran',
            'saldo_kas'
        ));
    }
        public function KelolaPesanan(Request $request)
    {
        $query = Pesanan::with(['user', 'jenisKertas']);

        // 1. Filter Status (jika ada yang dipilih)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 2. Fitur Pencarian (Kode Order atau Nama Pemesan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_order', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // withQueryString() agar filter tetap aktif saat berpindah pagination
        $pesanan = $query->latest()->paginate(10)->withQueryString();

        return view('admin.KelolaPesanan', compact('pesanan'));
    }


    public function Verifikasi()
    {
        $pesanan = Pesanan::with(['user', 'jenisKertas'])
            ->where('status', 'menunggu')
            ->latest()
            ->paginate(10);

        // Konsolidasi statistik verifikasi menjadi 1 query agregasi
        $today = today()->toDateString();
        $stats = Pesanan::selectRaw("
            COUNT(CASE WHEN status = 'menunggu' THEN 1 END) as jumlah_verifikasi,
            COUNT(CASE WHEN status = 'disetujui' AND DATE(updated_at) = ? THEN 1 END) as jumlah_selesai,
            COUNT(CASE WHEN status = 'ditolak' AND DATE(updated_at) = ? THEN 1 END) as jumlah_ditolak
        ", [$today, $today])->first();

        $jumlah_verifikasi = (int) ($stats->jumlah_verifikasi ?? 0);
        $jumlah_selesai = (int) ($stats->jumlah_selesai ?? 0);
        $jumlah_ditolak = (int) ($stats->jumlah_ditolak ?? 0);

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

    public function KelolaUser()
    {   
        $users = User::all();
        return view('admin.KelolaUser', compact('users'));
    }
    public function updateStatusUser(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'status' => 'required|in:aktif,nonaktif,active,inactive',
        ]);

        $user = User::where('id_user', $request->id_user)->first();

        if ($user) {
            $user->status = $request->status;
            $user->save();

            // Beri notifikasi
            return back()->with('success', "User " . $request->status . " berhasil!");
        } else {
            return back()->with('error', "User tidak ditemukan!");
        }
    }

}
