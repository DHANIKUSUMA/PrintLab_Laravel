<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisKertas;
use Illuminate\Http\Request;

class JenisKertasApiController extends Controller
{
    /**
     * Menampilkan daftar semua jenis kertas dan harga
     */
    public function index()
    {
        $kertas = JenisKertas::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar jenis kertas berhasil diambil',
            'data' => $kertas
        ], 200);
    }

    /**
     * Menampilkan detail satu jenis kertas
     */
    public function show($id)
    {
        $kertas = JenisKertas::find($id);

        if (!$kertas) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kertas tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail jenis kertas ditemukan',
            'data' => $kertas
        ], 200);
    }
}
