<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanApiController extends Controller
{
    /**
     * Mendapatkan rekapitulasi data laporan keuangan & operasional (JSON)
     */
    public function index(Request $request)
    {
        $periode = $request->get('periode', 'bulan_ini');
        $tipe = $request->get('tipe', 'semua');
        $today = Carbon::today();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $periode = 'custom';
        } else {
            switch ($periode) {
                case 'hari_ini':
                    $startDate = $today->copy()->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
                case '7_hari':
                    $startDate = $today->copy()->subDays(6)->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
                case 'tahun_ini':
                    $startDate = $today->copy()->startOfYear();
                    $endDate = $today->copy()->endOfYear();
                    break;
                case 'semua':
                    $startDate = Carbon::createFromTimestamp(0);
                    $endDate = $today->copy()->endOfDay();
                    break;
                case 'bulan_ini':
                default:
                    $startDate = $today->copy()->startOfMonth();
                    $endDate = $today->copy()->endOfMonth();
                    $periode = 'bulan_ini';
                    break;
            }
        }

        // Pemasukan
        $pesananQuery = Pesanan::with(['user:id_user,name,email', 'jenisKertas:id_jenis_kertas,nama_kertas,harga'])
            ->whereIn('status', ['disetujui', 'selesai'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        $pesanan = (clone $pesananQuery)->latest()->get();
        $total_pemasukan = (float)(clone $pesananQuery)->sum('total_biaya');
        $total_lembar = (int)(clone $pesananQuery)->sum('jumlah_lembar');
        $total_transaksi_pemasukan = $pesanan->count();

        // Pengeluaran
        $pengeluaranQuery = Pengeluaran::whereBetween('tanggal', [
            $startDate->toDateString(),
            $endDate->toDateString()
        ]);

        $pengeluaran = (clone $pengeluaranQuery)->latest('tanggal')->latest('created_at')->get();
        $total_pengeluaran = (float)(clone $pengeluaranQuery)->sum('jumlah');
        $total_transaksi_pengeluaran = $pengeluaran->count();

        // Finansial
        $laba_bersih = $total_pemasukan - $total_pengeluaran;
        $all_pemasukan = (float)Pesanan::whereIn('status', ['disetujui', 'selesai'])->sum('total_biaya');
        $all_pengeluaran = (float)Pengeluaran::sum('jumlah');
        $saldo_kas_saat_ini = $all_pemasukan - $all_pengeluaran;

        // Breakdown Pengeluaran per Kategori
        $breakdown_pengeluaran = Pengeluaran::whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->selectRaw('kategori, SUM(jumlah) as total, COUNT(*) as count')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        // Breakdown Kertas
        $breakdown_kertas = Pesanan::join('jenis_kertas', 'pesanan.id_jenis_kertas', '=', 'jenis_kertas.id_jenis_kertas')
            ->whereIn('pesanan.status', ['disetujui', 'selesai'])
            ->whereBetween('pesanan.created_at', [$startDate, $endDate])
            ->selectRaw('jenis_kertas.nama_kertas, SUM(pesanan.total_biaya) as total, SUM(pesanan.jumlah_lembar) as lembar, COUNT(*) as count')
            ->groupBy('jenis_kertas.nama_kertas')
            ->orderByDesc('total')
            ->get();

        // Buku Kas Gabungan
        $buku_kas = collect();

        if ($tipe === 'semua' || $tipe === 'pemasukan') {
            foreach ($pesanan as $item) {
                $buku_kas->push([
                    'id' => 'IN-' . $item->id_pesanan,
                    'tipe' => 'pemasukan',
                    'tanggal' => $item->created_at->toDateTimeString(),
                    'kode' => $item->kode_order,
                    'keterangan' => 'Pesanan ' . ($item->jenisKertas->nama_kertas ?? 'Cetak') . ' (' . $item->jumlah_lembar . ' lbr)',
                    'kategori' => $item->jenisKertas->nama_kertas ?? 'Cetak',
                    'masuk' => (float)$item->total_biaya,
                    'keluar' => 0,
                    'subjek' => $item->user->name ?? 'User',
                ]);
            }
        }

        if ($tipe === 'semua' || $tipe === 'pengeluaran') {
            foreach ($pengeluaran as $item) {
                $buku_kas->push([
                    'id' => 'OUT-' . $item->id_pengeluaran,
                    'tipe' => 'pengeluaran',
                    'tanggal' => Carbon::parse($item->tanggal)->setTimeFrom($item->created_at ?? now())->toDateTimeString(),
                    'kode' => 'EXP-' . str_pad($item->id_pengeluaran, 4, '0', STR_PAD_LEFT),
                    'keterangan' => $item->deskripsi,
                    'kategori' => $item->kategori,
                    'masuk' => 0,
                    'keluar' => (float)$item->jumlah,
                    'subjek' => 'Operasional',
                ]);
            }
        }

        $buku_kas = $buku_kas->sortByDesc('tanggal')->values();

        return response()->json([
            'success' => true,
            'message' => 'Data laporan berhasil dimuat',
            'data' => [
                'periode' => [
                    'tipe_periode' => $periode,
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $endDate->toDateString(),
                    'filter_tipe' => $tipe,
                ],
                'ringkasan' => [
                    'total_pemasukan' => $total_pemasukan,
                    'total_pengeluaran' => $total_pengeluaran,
                    'laba_bersih' => $laba_bersih,
                    'status_laba' => $laba_bersih >= 0 ? 'surplus' : 'defisit',
                    'total_lembar_cetak' => $total_lembar,
                    'total_transaksi_pemasukan' => $total_transaksi_pemasukan,
                    'total_transaksi_pengeluaran' => $total_transaksi_pengeluaran,
                    'total_semua_transaksi' => $total_transaksi_pemasukan + $total_transaksi_pengeluaran,
                    'saldo_kas_real_saat_ini' => $saldo_kas_saat_ini,
                ],
                'breakdown' => [
                    'pengeluaran_per_kategori' => $breakdown_pengeluaran,
                    'pemasukan_per_kertas' => $breakdown_kertas,
                ],
                'buku_kas' => $buku_kas,
            ]
        ], 200);
    }
}
