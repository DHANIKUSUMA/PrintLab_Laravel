<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Pengeluaran;
use App\Models\JenisKertas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman dashboard Laporan
     */
    public function index(Request $request)
    {
        $data = $this->getLaporanData($request);
        return view('admin.laporan', $data);
    }

    /**
     * Menampilkan halaman cetak laporan yang siap di-print/PDF
     */
    public function cetak(Request $request)
    {
        $data = $this->getLaporanData($request);
        return view('admin.CetakLaporan', $data);
    }

    /**
     * Mengekspor laporan ke format Excel (.xls)
     */
    public function exportExcel(Request $request)
    {
        $data = $this->getLaporanData($request);
        $filename = 'Laporan_Keuangan_PrintLab_' . $data['startDate'] . '_sd_' . $data['endDate'] . '.xls';

        return response()
            ->view('admin.ExportExcel', $data)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Memproses filter dan mengumpulkan seluruh data laporan
     */
    private function getLaporanData(Request $request): array
    {
        // Parameter filter periode preset
        $periode = $request->get('periode', 'bulan_ini');
        $tipe = $request->get('tipe', 'semua'); // 'semua', 'pemasukan', 'pengeluaran'

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

        // 1. Query Pemasukan (Pesanan yang disetujui / selesai)
        $pesananQuery = Pesanan::with(['user', 'jenisKertas'])
            ->whereIn('status', ['disetujui', 'selesai'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        $pesanan = (clone $pesananQuery)->latest()->get();
        $total_pemasukan = (clone $pesananQuery)->sum('total_biaya');
        $total_lembar = (clone $pesananQuery)->sum('jumlah_lembar');
        $total_transaksi_pemasukan = $pesanan->count();

        // 2. Query Pengeluaran
        $pengeluaranQuery = Pengeluaran::whereBetween('tanggal', [
            $startDate->toDateString(),
            $endDate->toDateString()
        ]);

        $pengeluaran = (clone $pengeluaranQuery)->latest('tanggal')->latest('created_at')->get();
        $total_pengeluaran = (clone $pengeluaranQuery)->sum('jumlah');
        $total_transaksi_pengeluaran = $pengeluaran->count();

        // 3. Kalkulasi Ringkasan Finansial
        $laba_bersih = $total_pemasukan - $total_pengeluaran;
        $total_semua_transaksi = $total_transaksi_pemasukan + $total_transaksi_pengeluaran;

        // Saldo Kas Keseluruhan Akumulasi (All Time)
        $all_pemasukan = Pesanan::whereIn('status', ['disetujui', 'selesai'])->sum('total_biaya');
        $all_pengeluaran = Pengeluaran::sum('jumlah');
        $saldo_kas_saat_ini = $all_pemasukan - $all_pengeluaran;

        // 4. Breakdown Pengeluaran per Kategori
        $breakdown_pengeluaran = Pengeluaran::whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->selectRaw('kategori, SUM(jumlah) as total, COUNT(*) as count')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        // 5. Breakdown Pemasukan per Jenis Kertas
        $breakdown_kertas = Pesanan::join('jenis_kertas', 'pesanan.id_jenis_kertas', '=', 'jenis_kertas.id_jenis_kertas')
            ->whereIn('pesanan.status', ['disetujui', 'selesai'])
            ->whereBetween('pesanan.created_at', [$startDate, $endDate])
            ->selectRaw('jenis_kertas.nama_kertas, SUM(pesanan.total_biaya) as total, SUM(pesanan.jumlah_lembar) as lembar, COUNT(*) as count')
            ->groupBy('jenis_kertas.nama_kertas')
            ->orderByDesc('total')
            ->get();

        // 6. Buku Kas Gabungan (Arus Kas Kronologis)
        $buku_kas = collect();

        if ($tipe === 'semua' || $tipe === 'pemasukan') {
            foreach ($pesanan as $item) {
                $buku_kas->push((object)[
                    'id' => 'IN-' . $item->id_pesanan,
                    'tipe' => 'pemasukan',
                    'tanggal' => $item->created_at,
                    'kode' => $item->kode_order,
                    'keterangan' => 'Pesanan ' . ($item->jenisKertas->nama_kertas ?? 'Cetak') . ' (' . $item->jumlah_lembar . ' lbr) - ' . ($item->user->name ?? 'User'),
                    'kategori' => $item->jenisKertas->nama_kertas ?? 'Cetak',
                    'masuk' => (float)$item->total_biaya,
                    'keluar' => 0,
                    'subjek' => $item->user->name ?? 'User',
                ]);
            }
        }

        if ($tipe === 'semua' || $tipe === 'pengeluaran') {
            foreach ($pengeluaran as $item) {
                $buku_kas->push((object)[
                    'id' => 'OUT-' . $item->id_pengeluaran,
                    'tipe' => 'pengeluaran',
                    'tanggal' => Carbon::parse($item->tanggal)->setTimeFrom($item->created_at ?? now()),
                    'kode' => 'EXP-' . str_pad($item->id_pengeluaran, 4, '0', STR_PAD_LEFT),
                    'keterangan' => $item->deskripsi,
                    'kategori' => $item->kategori,
                    'masuk' => 0,
                    'keluar' => (float)$item->jumlah,
                    'subjek' => 'Operasional',
                ]);
            }
        }

        // Urutkan buku kas kronologis terbaru terlebih dahulu
        $buku_kas = $buku_kas->sortByDesc('tanggal')->values();

        return [
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'periode' => $periode,
            'tipe' => $tipe,
            'pesanan' => $pesanan,
            'pengeluaran' => $pengeluaran,
            'buku_kas' => $buku_kas,
            'total_pemasukan' => $total_pemasukan,
            'total_pengeluaran' => $total_pengeluaran,
            'laba_bersih' => $laba_bersih,
            'total_lembar' => $total_lembar,
            'total_transaksi_pemasukan' => $total_transaksi_pemasukan,
            'total_transaksi_pengeluaran' => $total_transaksi_pengeluaran,
            'total_semua_transaksi' => $total_semua_transaksi,
            'saldo_kas_saat_ini' => $saldo_kas_saat_ini,
            'breakdown_pengeluaran' => $breakdown_pengeluaran,
            'breakdown_kertas' => $breakdown_kertas,
        ];
    }
}
