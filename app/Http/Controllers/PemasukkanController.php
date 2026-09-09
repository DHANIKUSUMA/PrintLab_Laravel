<?php

namespace App\Http\Controllers;

use App\Models\JenisKertas;
use App\Models\Pesanan;
use Illuminate\Http\Request;


class PemasukkanController extends Controller
{
    public function Pemasukkan()
    {   
        $pesanan = Pesanan::with(['user', 'JenisKertas'])->whereIn('status', ['disetujui','selesai'])->latest()->paginate(10);

        $saldo_kas = Pesanan::where('status', 'disetujui')->sum('total_biaya');
        return view('admin.pemasukkan', compact('pesanan', 'saldo_kas'));

 
    }
}
