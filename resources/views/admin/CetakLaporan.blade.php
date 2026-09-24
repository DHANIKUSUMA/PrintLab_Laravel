<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan PrintLab - {{ $startDate }} s/d {{ $endDate }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .no-print {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            max-width: 900px;
            margin: 0 auto 20px auto;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #2563eb;
            color: #ffffff;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }
        .btn-secondary:hover {
            background: #cbd5e1;
        }

        /* HEADER LAPORAN */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .brand-title {
            font-size: 24px;
            font-weight: 800;
            color: #1e40af;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .report-info {
            text-align: right;
        }

        .report-info h2 {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
        }

        .report-info p {
            font-size: 11px;
            color: #475569;
            margin-top: 3px;
        }

        /* SUMMARY BOXES */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .summary-card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 16px;
            background: #f8fafc;
        }

        .summary-card .label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .summary-card .value {
            font-size: 18px;
            font-weight: 800;
            margin-top: 4px;
        }

        .summary-card.in .value { color: #059669; }
        .summary-card.out .value { color: #dc2626; }
        .summary-card.net .value { color: #2563eb; }

        .summary-card .sub {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* TABLES */
        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 7px 10px;
            font-size: 11px;
        }

        th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.3px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .text-green { color: #059669; font-weight: bold; }
        .text-red { color: #dc2626; font-weight: bold; }

        /* SIGNATURE SECTION */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-box {
            width: 200px;
            text-align: center;
        }

        .signature-box .date {
            margin-bottom: 50px;
            font-size: 11px;
            color: #334155;
        }

        .signature-box .name {
            font-weight: bold;
            text-decoration: underline;
            font-size: 12px;
        }

        .signature-box .role {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* PRINT MEDIA STYLES */
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
                border-radius: 0;
            }
            th {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- ACTION BAR DI ATAS (HIDDEN SAAT PRINT) -->
    <div class="no-print">
        <a href="{{ route('admin.laporan', request()->query()) }}" class="btn btn-secondary">
            &larr; Kembali ke Panel Laporan
        </a>
        <div>
            <button onclick="window.print()" class="btn btn-primary">
                &#128438; Cetak Dokumen / Simpan PDF
            </button>
        </div>
    </div>

    <div class="container">
        <!-- HEADER SURAT / LAPORAN -->
        <div class="report-header">
            <div>
                <div class="brand-title">PrintLab Percetakan</div>
                <div class="brand-subtitle">Layanan Digital Printing & Fotokopi Cepat</div>
                <div class="brand-subtitle">Email: info@printlab.com | Telp: (021) 555-0199</div>
            </div>
            <div class="report-info">
                <h2>Laporan Arus Kas & Operasional</h2>
                <p>Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</strong></p>
                <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        <!-- REKAPITULASI KEUANGAN -->
        <div class="summary-grid">
            <div class="summary-card in">
                <div class="label">Total Pemasukan</div>
                <div class="value">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</div>
                <div class="sub">{{ $total_transaksi_pemasukan }} Pesanan Selesai ({{ number_format($total_lembar, 0, ',', '.') }} lbr)</div>
            </div>
            <div class="summary-card out">
                <div class="label">Total Pengeluaran</div>
                <div class="value">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</div>
                <div class="sub">{{ $total_transaksi_pengeluaran }} Catatan Operasional</div>
            </div>
            <div class="summary-card net">
                <div class="label">Laba Bersih Periode</div>
                <div class="value">{{ $laba_bersih < 0 ? '-' : '' }}Rp {{ number_format(abs($laba_bersih), 0, ',', '.') }}</div>
                <div class="sub">{{ $laba_bersih >= 0 ? 'Surplus Operasional' : 'Defisit Operasional' }}</div>
            </div>
        </div>

        <!-- TABEL BUKU KAS GABUNGAN -->
        <div class="section-title">Rincian Arus Transaksi (Buku Kas Gabungan)</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;" class="text-center">No</th>
                    <th style="width: 90px;">Tanggal</th>
                    <th style="width: 100px;">Kode / Ref</th>
                    <th>Keterangan / Transaksi</th>
                    <th style="width: 100px;">Kategori</th>
                    <th style="width: 110px;" class="text-right">Masuk (+)</th>
                    <th style="width: 110px;" class="text-right">Keluar (-)</th>
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
                        <td class="text-right {{ $row->masuk > 0 ? 'text-green' : '' }}">
                            {{ $row->masuk > 0 ? 'Rp ' . number_format($row->masuk, 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-right {{ $row->keluar > 0 ? 'text-red' : '' }}">
                            {{ $row->keluar > 0 ? 'Rp ' . number_format($row->keluar, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 20px; color: #94a3b8;">
                            Tidak ada catatan transaksi pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f8fafc; font-weight: bold;">
                    <td colspan="5" class="text-right">TOTAL TRANSAKSI PERIODE:</td>
                    <td class="text-right text-green">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
                    <td class="text-right text-red">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
                </tr>
                <tr style="background-color: #f1f5f9; font-weight: bold; font-size: 12px;">
                    <td colspan="5" class="text-right">LABA BERSIH (MASUK - KELUAR):</td>
                    <td colspan="2" class="text-right" style="color: {{ $laba_bersih >= 0 ? '#2563eb' : '#dc2626' }};">
                        {{ $laba_bersih < 0 ? '-' : '' }}Rp {{ number_format(abs($laba_bersih), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- LEMBAR TANDA TANGAN -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="date">Mengetahui,</div>
                <div class="name">Pimpinan / Pemilik</div>
                <div class="role">PrintLab Digital</div>
            </div>
            <div class="signature-box">
                <div class="date">Dicetak oleh, {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</div>
                <div class="name">{{ Auth::user()->name ?? 'Administrator' }}</div>
                <div class="role">Petugas Administrasi</div>
            </div>
        </div>
    </div>

    <script>
        // Auto trigger print saat halaman siap jika diakses langsung
        window.addEventListener('DOMContentLoaded', () => {
            // Uncomment jika ingin auto-print saat dibuka:
            // window.print();
        });
    </script>
</body>
</html>
