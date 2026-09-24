<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Keuangan PrintLab</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }
        .header-title {
            font-size: 16pt;
            font-weight: bold;
            color: #1e40af;
        }
        .header-sub {
            font-size: 10pt;
            color: #475569;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            background-color: #2563eb;
            color: #ffffff;
            padding: 6px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th {
            background-color: #dbeafe;
            color: #1e3a8a;
            font-weight: bold;
            border: 1px solid #93c5fd;
            padding: 6px 8px;
            text-align: left;
        }
        td {
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
            vertical-align: middle;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .bg-green {
            background-color: #ecfdf5;
            color: #065f46;
            font-weight: bold;
        }
        .bg-red {
            background-color: #fef2f2;
            color: #991b1b;
            font-weight: bold;
        }
        .bg-summary {
            background-color: #f8fafc;
            font-weight: bold;
        }
        .num-format {
            mso-number-format: "\#\,\#\#0";
        }
    </style>
</head>
<body>

    <!-- KOP LAPORAN -->
    <table>
        <tr>
            <td colspan="7" class="header-title">PRINTLAB PERCETAKAN DIGITAL</td>
        </tr>
        <tr>
            <td colspan="7" class="header-sub">LAPORAN ARUS KAS &amp; OPERASIONAL</td>
        </tr>
        <tr>
            <td colspan="7" class="header-sub">
                Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="7" class="header-sub">
                Tanggal Unduh: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB | Diekspor oleh: {{ Auth::user()->name ?? 'Administrator' }}
            </td>
        </tr>
    </table>

    <br />

    <!-- RINGKASAN FINANSIAL -->
    <table>
        <tr>
            <th colspan="2" style="background-color: #1e40af; color: #ffffff;">RINGKASAN FINANSIAL PERIODE</th>
        </tr>
        <tr>
            <td style="width: 250px;">Total Pemasukan (Pesanan Selesai)</td>
            <td class="text-right font-bold bg-green">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Pengeluaran Operasional</td>
            <td class="text-right font-bold bg-red">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Laba Bersih Periode</td>
            <td class="text-right font-bold" style="color: {{ $laba_bersih >= 0 ? '#1e40af' : '#b91c1c' }};">
                {{ $laba_bersih < 0 ? '-' : '' }}Rp {{ number_format(abs($laba_bersih), 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td>Total Lembar Kertas Dicetak</td>
            <td class="text-right font-bold">{{ number_format($total_lembar, 0, ',', '.') }} Lembar</td>
        </tr>
        <tr>
            <td>Total Volume Pesanan Masuk</td>
            <td class="text-right font-bold">{{ number_format($total_transaksi_pemasukan) }} Transaksi</td>
        </tr>
        <tr>
            <td>Total Catatan Pengeluaran</td>
            <td class="text-right font-bold">{{ number_format($total_transaksi_pengeluaran) }} Transaksi</td>
        </tr>
        <tr>
            <td>Sisa Saldo Kas Real Saat Ini (All-Time)</td>
            <td class="text-right font-bold">Rp {{ number_format($saldo_kas_saat_ini, 0, ',', '.') }}</td>
        </tr>
    </table>

    <br />

    <!-- TABEL 1: BUKU KAS GABUNGAN -->
    <table>
        <thead>
            <tr>
                <th colspan="7" class="section-title">1. BUKU KAS GABUNGAN (MUTASI ARUS KAS)</th>
            </tr>
            <tr>
                <th style="width: 40px;" class="text-center">No</th>
                <th style="width: 140px;">Tanggal &amp; Waktu</th>
                <th style="width: 120px;">Kode / Ref</th>
                <th style="width: 260px;">Keterangan / Transaksi</th>
                <th style="width: 140px;">Kategori / Kertas</th>
                <th style="width: 140px;" class="text-right">Pemasukan (+)</th>
                <th style="width: 140px;" class="text-right">Pengeluaran (-)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($buku_kas as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d/m/Y H:i') }}</td>
                    <td class="font-bold">{{ $row->kode }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>{{ $row->kategori }}</td>
                    <td class="text-right {{ $row->masuk > 0 ? 'bg-green' : '' }}">
                        {{ $row->masuk > 0 ? number_format($row->masuk, 0, ',', '.') : '-' }}
                    </td>
                    <td class="text-right {{ $row->keluar > 0 ? 'bg-red' : '' }}">
                        {{ $row->keluar > 0 ? number_format($row->keluar, 0, ',', '.') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="bg-summary">
                <td colspan="5" class="text-right">TOTAL MUTASI PERIODE:</td>
                <td class="text-right bg-green">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
                <td class="text-right bg-red">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-summary" style="font-size: 12pt;">
                <td colspan="5" class="text-right">LABA BERSIH:</td>
                <td colspan="2" class="text-right" style="color: {{ $laba_bersih >= 0 ? '#1e40af' : '#b91c1c' }};">
                    {{ $laba_bersih < 0 ? '-' : '' }}Rp {{ number_format(abs($laba_bersih), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <br />

    <!-- TABEL 2: DETAIL PEMASUKAN -->
    <table>
        <thead>
            <tr>
                <th colspan="7" class="section-title">2. RINCIAN PESANAN (PEMASUKAN)</th>
            </tr>
            <tr>
                <th style="width: 40px;" class="text-center">No</th>
                <th style="width: 120px;">Kode Order</th>
                <th style="width: 180px;">Nama Pemesan</th>
                <th style="width: 140px;">Jenis Kertas</th>
                <th style="width: 100px;" class="text-right">Lembar</th>
                <th style="width: 140px;">Tanggal Selesai</th>
                <th style="width: 140px;" class="text-right">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanan as $idx => $row)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $row->kode_order }}</td>
                    <td>{{ $row->user->name ?? 'User' }}</td>
                    <td>{{ $row->jenisKertas->nama_kertas ?? '-' }}</td>
                    <td class="text-right">{{ number_format($row->jumlah_lembar) }}</td>
                    <td>{{ $row->created_at->translatedFormat('d/m/Y H:i') }}</td>
                    <td class="text-right font-bold bg-green">Rp {{ number_format($row->total_biaya, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data pemasukan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="bg-summary">
                <td colspan="6" class="text-right">SUBTOTAL PEMASUKAN:</td>
                <td class="text-right font-bold bg-green">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <br />

    <!-- TABEL 3: DETAIL PENGELUARAN -->
    <table>
        <thead>
            <tr>
                <th colspan="5" class="section-title">3. RINCIAN PENGELUARAN OPERASIONAL</th>
            </tr>
            <tr>
                <th style="width: 40px;" class="text-center">No</th>
                <th style="width: 120px;">Ref ID</th>
                <th style="width: 280px;">Deskripsi Pengeluaran</th>
                <th style="width: 160px;">Kategori</th>
                <th style="width: 140px;">Tanggal</th>
                <th style="width: 140px;" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengeluaran as $idx => $row)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">EXP-{{ str_pad($row->id_pengeluaran, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $row->deskripsi }}</td>
                    <td>{{ $row->kategori }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d/m/Y') }}</td>
                    <td class="text-right font-bold bg-red">Rp {{ number_format($row->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pengeluaran pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="bg-summary">
                <td colspan="5" class="text-right">SUBTOTAL PENGELUARAN:</td>
                <td class="text-right font-bold bg-red">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
