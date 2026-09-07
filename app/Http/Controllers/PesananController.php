<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\JenisKertas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    // Menampilkan daftar pesanan beserta relasinya
    public function index()
    {
        $pesanans = Pesanan::with(['user', 'jenisKertas'])->get();
        return response()->json($pesanans); // Bisa diubah ke return view() jika sudah ada Blade
    }

    // Menyimpan data pesanan baru
    public function store(Request $request)
    {
        $request->validate([
            'id_jenis_kertas' => 'required|exists:jenis_kertas,id_jenis_kertas',
            'jumlah_lembar' => 'required|integer|min:1',
            'metode_pembayaran' => 'required',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Ambil harga kertas untuk menghitung total biaya secara otomatis
        $kertas = JenisKertas::findOrFail($request->id_jenis_kertas);
        $totalBiaya = $request->jumlah_lembar * $kertas->harga;

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        Pesanan::create([
            'id_user' => auth()->id(), // Otomatis ID user yang sedang login
            'id_jenis_kertas' => $request->id_jenis_kertas,
            'jumlah_lembar' => $request->jumlah_lembar,
            'total_biaya' => $totalBiaya,
            'status' => 'menunggu',
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => $buktiPath,
            'kode_order' => 'ORD-' . strtoupper(Str::random(6)),
        ]);

        return redirect()->route('dashboard')->with('success', 'Pesanan berhasil dibuat!');
    }

    public function create()
    {
        $jenisKertas = JenisKertas::all();
        return view('pesanan.create', compact('jenisKertas'));
    }
}
