<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\JenisKertas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PesananApiController extends Controller
{
    /**
     * Menampilkan daftar pesanan dengan filter & search
     */
    public function index(Request $request)
    {
        $query = Pesanan::with(['user:id_user,name,email', 'jenisKertas:id_jenis_kertas,nama_kertas,harga']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_order', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $perPage = $request->get('per_page', 15);
        $pesanan = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar pesanan berhasil diambil',
            'data' => $pesanan
        ], 200);
    }

    /**
     * Menampilkan detail pesanan berdasarkan ID atau Kode Order
     */
    public function show($identifier)
    {
        $pesanan = Pesanan::with(['user:id_user,name,email', 'jenisKertas:id_jenis_kertas,nama_kertas,harga'])
            ->where('id_pesanan', $identifier)
            ->orWhere('kode_order', $identifier)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pesanan ditemukan',
            'data' => $pesanan
        ], 200);
    }

    /**
     * Membuat pesanan baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|exists:users,id_user',
            'id_jenis_kertas' => 'required|exists:jenis_kertas,id_jenis_kertas',
            'jumlah_lembar' => 'required|integer|min:1',
            'metode_pembayaran' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $kertas = JenisKertas::find($request->id_jenis_kertas);
        $totalBiaya = $kertas->harga * $request->jumlah_lembar;
        $kodeOrder = 'PL-' . strtoupper(Str::random(6)) . '-' . date('dmy');

        $pesanan = Pesanan::create([
            'id_user' => $request->id_user,
            'id_jenis_kertas' => $request->id_jenis_kertas,
            'jumlah_lembar' => $request->jumlah_lembar,
            'total_biaya' => $totalBiaya,
            'status' => 'menunggu',
            'metode_pembayaran' => $request->metode_pembayaran ?? 'transfer',
            'bukti_pembayaran' => $request->bukti_pembayaran,
            'kode_order' => $kodeOrder,
        ]);

        $pesanan->load(['user:id_user,name,email', 'jenisKertas:id_jenis_kertas,nama_kertas,harga']);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'data' => $pesanan
        ], 201);
    }

    /**
     * Mengubah status verifikasi pesanan (disetujui / ditolak)
     */
    public function updateStatus(Request $request, $kode_order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:menunggu,disetujui,ditolak,selesai',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        $pesanan = Pesanan::where('kode_order', $kode_order)->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        $pesanan->status = $request->status;
        $pesanan->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui ke: ' . $request->status,
            'data' => $pesanan
        ], 200);
    }
}
