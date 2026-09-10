<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function Pengeluaran()
    {
        $pengeluaran = Pengeluaran::latest('tanggal')->latest('created_at')->paginate(10);
        $total_pemasukan = Pesanan::whereIn('status', ['disetujui', 'selesai'])->sum('total_biaya');
        $total_pengeluaran = Pengeluaran::sum('jumlah');
        $saldo_kas = $total_pemasukan - $total_pengeluaran;
        
        $pengeluaran_bulan_ini = Pengeluaran::whereMonth('tanggal', now()->month)
                                            ->whereYear('tanggal', now()->year)
                                            ->sum('jumlah');
        $total_transaksi = Pengeluaran::count();

        return view('admin.Pengeluaran', compact('pengeluaran', 'total_pengeluaran', 'saldo_kas', 'pengeluaran_bulan_ini', 'total_transaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required',
            'kategori'  => 'required',
            'jumlah'    => 'required|numeric|min:1',
            'tanggal'   => 'required|date',
        ]);

        // Cek apakah pengeluaran melebihi sisa saldo kas
        $total_pemasukan = Pesanan::whereIn('status', ['disetujui', 'selesai'])->sum('total_biaya');
        $total_pengeluaran = Pengeluaran::sum('jumlah');
        $saldo_kas = $total_pemasukan - $total_pengeluaran;

        if ($request->jumlah > $saldo_kas) {
            return back()
                ->withInput()
                ->with('error', 'Pengeluaran gagal! Nominal pengeluaran (Rp ' . number_format($request->jumlah, 0, ',', '.') . ') melebihi saldo kas yang tersedia (Rp ' . number_format($saldo_kas, 0, ',', '.') . ').');
        }

        Pengeluaran::create([
            'deskripsi' => $request->deskripsi,
            'kategori'  => $request->kategori,
            'jumlah'    => $request->jumlah,
            'tanggal'   => $request->tanggal,
        ]);

        return redirect()->route('admin.pengeluaran')->with('success', 'Pengeluaran berhasil ditambahkan');
    }
}
