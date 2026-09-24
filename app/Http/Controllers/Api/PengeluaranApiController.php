<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengeluaranApiController extends Controller
{
    /**
     * Menampilkan daftar riwayat pengeluaran
     */
    public function index(Request $request)
    {
        $query = Pengeluaran::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $pengeluaran = $query->latest('tanggal')->latest('created_at')->paginate($perPage);

        $total_pemasukan = (float)Pesanan::whereIn('status', ['disetujui', 'selesai'])->sum('total_biaya');
        $total_pengeluaran = (float)Pengeluaran::sum('jumlah');
        $saldo_kas = $total_pemasukan - $total_pengeluaran;

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengeluaran berhasil diambil',
            'meta' => [
                'saldo_kas_tersedia' => $saldo_kas,
                'total_pengeluaran_all_time' => $total_pengeluaran,
            ],
            'data' => $pengeluaran
        ], 200);
    }

    /**
     * Menambahkan catatan pengeluaran baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'jumlah' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek saldo kas tersedia
        $total_pemasukan = (float)Pesanan::whereIn('status', ['disetujui', 'selesai'])->sum('total_biaya');
        $total_pengeluaran = (float)Pengeluaran::sum('jumlah');
        $saldo_kas = $total_pemasukan - $total_pengeluaran;

        if ($request->jumlah > $saldo_kas) {
            return response()->json([
                'success' => false,
                'message' => 'Pengeluaran gagal! Nominal (Rp ' . number_format($request->jumlah, 0, ',', '.') . ') melebihi saldo kas tersedia (Rp ' . number_format($saldo_kas, 0, ',', '.') . ').'
            ], 400);
        }

        $pengeluaran = Pengeluaran::create([
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengeluaran berhasil dicatat',
            'data' => $pengeluaran
        ], 201);
    }

    /**
     * Menampilkan detail satu catatan pengeluaran
     */
    public function show($id)
    {
        $pengeluaran = Pengeluaran::find($id);

        if (!$pengeluaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengeluaran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pengeluaran ditemukan',
            'data' => $pengeluaran
        ], 200);
    }
}
