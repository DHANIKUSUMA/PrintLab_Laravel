@include('layouts.header', ['title' => 'Laporan Keuangan & Operasional - PrintLab'])

<body class="font-sans bg-brand-50 min-h-screen text-slate-900 antialiased">
  <div class="flex min-h-screen" x-data="{ sidebarOpen: false, activeTab: 'buku_kas' }">
    
    <!-- Mobile Backdrop Overlay -->
    <div 
      x-show="sidebarOpen" 
      x-cloak
      @click="sidebarOpen = false" 
      class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden">
    </div>

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 min-w-0 flex flex-col min-h-screen">

      <!-- TOPBAR -->
      <header class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-slate-100">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 rounded-xl text-slate-600 hover:bg-slate-100 transition">
              <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h1 id="pageTitle" class="text-lg font-bold text-slate-800 tracking-tight">Laporan Keuangan & Operasional</h1>
          </div>
          <div class="flex items-center gap-3">
            <span class="hidden sm:inline text-sm text-slate-500">Halo, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span></span>
          </div>
        </div>
      </header>

      <!-- MAIN CONTENT -->
      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 w-full flex-1 space-y-6">

        <!-- HEADER TITLE & ACTION BUTTONS -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-800">Rekapitulasi Laporan</h2>
            <p class="text-sm text-slate-500 mt-1">
              Pantau arus kas, pemasukan percetakan, pengeluaran operasional, dan laba bersih.
            </p>
          </div>
          <div class="flex items-center gap-2">
            <!-- Tombol Export Excel -->
            <a 
              href="{{ route('admin.laporan.excel', request()->query()) }}" 
              class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm shadow-emerald-600/20 hover:shadow-md transition text-sm">
              <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
              <span>Export Excel</span>
            </a>

            <!-- Tombol Cetak Laporan -->
            <a 
              href="{{ route('admin.laporan.cetak', request()->query()) }}" 
              target="_blank"
              class="inline-flex items-center justify-center gap-2 bg-white border border-slate-200 text-slate-700 hover:text-brand-600 hover:border-brand-200 hover:bg-brand-50/50 font-semibold px-4 py-2.5 rounded-xl shadow-sm transition text-sm">
              <i data-lucide="printer" class="w-4 h-4"></i>
              <span>Cetak / PDF</span>
            </a>
          </div>
        </div>

        <!-- CARD FILTER PERIODE & TANGGAL -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-5 sm:p-6 space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                <i data-lucide="filter" class="w-4 h-4"></i>
              </div>
              <h3 class="font-bold text-slate-800 text-sm sm:text-base">Filter Periode Laporan</h3>
            </div>
            <span class="text-xs text-slate-400">
              Rentang Aktif: <strong class="text-slate-700">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</strong> s/d <strong class="text-slate-700">{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</strong>
            </span>
          </div>

          <!-- Quick Preset Buttons -->
          <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 mr-1">Preset:</span>
            <a href="{{ route('admin.laporan', ['periode' => 'hari_ini', 'tipe' => $tipe]) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $periode === 'hari_ini' ? 'bg-brand-600 text-white shadow-sm shadow-brand-600/30' : 'bg-slate-100 text-slate-600 hover:bg-brand-50 hover:text-brand-600' }}">
              Hari Ini
            </a>
            <a href="{{ route('admin.laporan', ['periode' => '7_hari', 'tipe' => $tipe]) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $periode === '7_hari' ? 'bg-brand-600 text-white shadow-sm shadow-brand-600/30' : 'bg-slate-100 text-slate-600 hover:bg-brand-50 hover:text-brand-600' }}">
              7 Hari Terakhir
            </a>
            <a href="{{ route('admin.laporan', ['periode' => 'bulan_ini', 'tipe' => $tipe]) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $periode === 'bulan_ini' ? 'bg-brand-600 text-white shadow-sm shadow-brand-600/30' : 'bg-slate-100 text-slate-600 hover:bg-brand-50 hover:text-brand-600' }}">
              Bulan Ini
            </a>
            <a href="{{ route('admin.laporan', ['periode' => 'tahun_ini', 'tipe' => $tipe]) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $periode === 'tahun_ini' ? 'bg-brand-600 text-white shadow-sm shadow-brand-600/30' : 'bg-slate-100 text-slate-600 hover:bg-brand-50 hover:text-brand-600' }}">
              Tahun Ini
            </a>
            <a href="{{ route('admin.laporan', ['periode' => 'semua', 'tipe' => $tipe]) }}" 
               class="px-3 py-1.5 rounded-xl text-xs font-semibold transition {{ $periode === 'semua' ? 'bg-brand-600 text-white shadow-sm shadow-brand-600/30' : 'bg-slate-100 text-slate-600 hover:bg-brand-50 hover:text-brand-600' }}">
              Semua Waktu
            </a>
          </div>

          <!-- Date Form Inputs -->
          <form action="{{ route('admin.laporan') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 pt-2">
            <div>
              <label for="start_date" class="block text-xs font-bold text-slate-600 mb-1">Tanggal Mulai</label>
              <input 
                type="date" 
                id="start_date" 
                name="start_date" 
                value="{{ $startDate }}"
                class="w-full px-3 py-2 text-sm bg-slate-50/50 border border-slate-300 rounded-xl focus:outline-none focus:border-brand-600 focus:bg-white focus:ring-2 focus:ring-brand-100 transition text-slate-800">
            </div>
            <div>
              <label for="end_date" class="block text-xs font-bold text-slate-600 mb-1">Tanggal Selesai</label>
              <input 
                type="date" 
                id="end_date" 
                name="end_date" 
                value="{{ $endDate }}"
                class="w-full px-3 py-2 text-sm bg-slate-50/50 border border-slate-300 rounded-xl focus:outline-none focus:border-brand-600 focus:bg-white focus:ring-2 focus:ring-brand-100 transition text-slate-800">
            </div>
            <div>
              <label for="tipe" class="block text-xs font-bold text-slate-600 mb-1">Tipe Transaksi</label>
              <select 
                id="tipe" 
                name="tipe"
                class="w-full px-3 py-2 text-sm bg-slate-50/50 border border-slate-300 rounded-xl focus:outline-none focus:border-brand-600 focus:bg-white focus:ring-2 focus:ring-brand-100 transition text-slate-800">
                <option value="semua" {{ $tipe === 'semua' ? 'selected' : '' }}>Semua (Pemasukan & Pengeluaran)</option>
                <option value="pemasukan" {{ $tipe === 'pemasukan' ? 'selected' : '' }}>Hanya Pemasukan</option>
                <option value="pengeluaran" {{ $tipe === 'pengeluaran' ? 'selected' : '' }}>Hanya Pengeluaran</option>
              </select>
            </div>
            <div class="flex items-end gap-2">
              <button 
                type="submit" 
                class="flex-1 inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-4 py-2 rounded-xl text-sm shadow-md shadow-brand-600/20 hover:shadow-lg transition">
                <i data-lucide="search" class="w-4 h-4"></i>
                <span>Terapkan</span>
              </button>
              <a 
                href="{{ route('admin.laporan') }}" 
                title="Reset Filter"
                class="inline-flex items-center justify-center p-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
              </a>
            </div>
          </form>
        </div>

        <!-- 4 STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
          
          <!-- Card 1: Total Pemasukan -->
          <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-5 sm:p-6 transition hover:shadow-md">
            <div class="flex items-center justify-between">
              <p class="text-xs uppercase tracking-wider font-semibold text-slate-400">Total Pemasukan</p>
              <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i data-lucide="arrow-down-left" class="w-5 h-5"></i>
              </div>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-emerald-600 mt-2">
              Rp {{ number_format($total_pemasukan, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-slate-400 mt-1">
              Dari <strong class="text-slate-700 font-semibold">{{ $total_transaksi_pemasukan }}</strong> pesanan selesai
            </p>
          </div>

          <!-- Card 2: Total Pengeluaran -->
          <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-5 sm:p-6 transition hover:shadow-md">
            <div class="flex items-center justify-between">
              <p class="text-xs uppercase tracking-wider font-semibold text-slate-400">Total Pengeluaran</p>
              <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center">
                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
              </div>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-rose-500 mt-2">
              Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-slate-400 mt-1">
              Dari <strong class="text-slate-700 font-semibold">{{ $total_transaksi_pengeluaran }}</strong> catatan operasional
            </p>
          </div>

          <!-- Card 3: Laba Bersih Periode -->
          <div class="bg-brand-600 rounded-2xl shadow-lg shadow-brand-600/20 p-5 sm:p-6 text-white transition hover:shadow-xl">
            <div class="flex items-center justify-between">
              <p class="text-xs uppercase tracking-wider font-semibold text-brand-100">Laba Bersih Periode</p>
              <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white">
                <i data-lucide="trending-up" class="w-5 h-5"></i>
              </div>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold mt-2">
              {{ $laba_bersih < 0 ? '-' : '' }}Rp {{ number_format(abs($laba_bersih), 0, ',', '.') }}
            </h3>
            <p class="text-xs text-brand-100/90 mt-1 flex items-center gap-1.5">
              @if($laba_bersih >= 0)
                <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-white/20 text-[10px] font-bold">SURPLUS</span>
                <span>Pemasukan &gt; Pengeluaran</span>
              @else
                <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-rose-500/80 text-[10px] font-bold">DEFISIT</span>
                <span>Pengeluaran &gt; Pemasukan</span>
              @endif
            </p>
          </div>

          <!-- Card 4: Produksi Lembar & Kas -->
          <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-5 sm:p-6 transition hover:shadow-md">
            <div class="flex items-center justify-between">
              <p class="text-xs uppercase tracking-wider font-semibold text-slate-400">Total Produksi</p>
              <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-lucide="layers" class="w-5 h-5"></i>
              </div>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-slate-800 mt-2">
              {{ number_format($total_lembar, 0, ',', '.') }} <span class="text-sm font-normal text-slate-400">Lembar</span>
            </h3>
            <p class="text-xs text-slate-400 mt-1">
              Saldo Kas Real: <strong class="text-slate-700 font-semibold">Rp {{ number_format($saldo_kas_saat_ini, 0, ',', '.') }}</strong>
            </p>
          </div>

        </div>

        <!-- TAB NAVIGATION & DATA TABLES -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] overflow-hidden">
          
          <!-- TAB HEADER -->
          <div class="border-b border-slate-100 px-5 sm:px-6 pt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <!-- Navigation Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
              <button 
                @click="activeTab = 'buku_kas'" 
                :class="activeTab === 'buku_kas' ? 'text-brand-600 border-brand-600 font-bold bg-brand-50/50' : 'text-slate-500 hover:text-slate-800 border-transparent'"
                class="px-4 py-2.5 text-sm rounded-t-xl border-b-2 transition flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="book-open" class="w-4 h-4"></i>
                <span>Buku Kas Gabungan</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">{{ $buku_kas->count() }}</span>
              </button>

              <button 
                @click="activeTab = 'pemasukan'" 
                :class="activeTab === 'pemasukan' ? 'text-brand-600 border-brand-600 font-bold bg-brand-50/50' : 'text-slate-500 hover:text-slate-800 border-transparent'"
                class="px-4 py-2.5 text-sm rounded-t-xl border-b-2 transition flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="arrow-down-circle" class="w-4 h-4 text-emerald-500"></i>
                <span>Detail Pemasukan</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-semibold">{{ $pesanan->count() }}</span>
              </button>

              <button 
                @click="activeTab = 'pengeluaran'" 
                :class="activeTab === 'pengeluaran' ? 'text-brand-600 border-brand-600 font-bold bg-brand-50/50' : 'text-slate-500 hover:text-slate-800 border-transparent'"
                class="px-4 py-2.5 text-sm rounded-t-xl border-b-2 transition flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="arrow-up-circle" class="w-4 h-4 text-rose-500"></i>
                <span>Detail Pengeluaran</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 font-semibold">{{ $pengeluaran->count() }}</span>
              </button>

              <button 
                @click="activeTab = 'analisis'" 
                :class="activeTab === 'analisis' ? 'text-brand-600 border-brand-600 font-bold bg-brand-50/50' : 'text-slate-500 hover:text-slate-800 border-transparent'"
                class="px-4 py-2.5 text-sm rounded-t-xl border-b-2 transition flex items-center gap-2 whitespace-nowrap">
                <i data-lucide="pie-chart" class="w-4 h-4 text-purple-500"></i>
                <span>Ringkasan Kategori</span>
              </button>
            </div>

            <!-- Client-side Quick Search (Active in table tabs) -->
            <div x-show="activeTab !== 'analisis'" class="relative pb-3 sm:pb-0">
              <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
              <input 
                type="text" 
                id="tableSearchInput" 
                placeholder="Cari dalam tabel..." 
                class="text-sm pl-9 pr-3 py-1.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-600 w-full sm:w-56 transition">
            </div>
          </div>

          <!-- TAB 1: BUKU KAS GABUNGAN -->
          <div x-show="activeTab === 'buku_kas'" class="p-0">
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead class="bg-brand-50/70 text-slate-500 text-xs uppercase tracking-wide">
                  <tr class="text-left border-b border-slate-100">
                    <th class="px-5 py-3.5 font-semibold">Tanggal & Waktu</th>
                    <th class="px-5 py-3.5 font-semibold">Kode Transaksi</th>
                    <th class="px-5 py-3.5 font-semibold">Keterangan / Item</th>
                    <th class="px-5 py-3.5 font-semibold">Kategori / Kertas</th>
                    <th class="px-5 py-3.5 font-semibold text-center">Tipe</th>
                    <th class="px-5 py-3.5 font-semibold text-right">Pemasukan (+)</th>
                    <th class="px-5 py-3.5 font-semibold text-right">Pengeluaran (-)</th>
                  </tr>
                </thead>
                <tbody id="bukuKasBody" class="divide-y divide-slate-100">
                  @forelse($buku_kas as $row)
                    <tr class="hover:bg-slate-50/70 transition searchable-row">
                      <td class="px-5 py-3.5 text-slate-500 text-xs whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y, H:i') }}
                      </td>
                      <td class="px-5 py-3.5 font-bold text-slate-800 text-xs whitespace-nowrap">
                        {{ $row->kode }}
                      </td>
                      <td class="px-5 py-3.5 text-slate-700">
                        <span class="font-medium block text-slate-800">{{ $row->keterangan }}</span>
                        <span class="text-xs text-slate-400">Subjek: {{ $row->subjek }}</span>
                      </td>
                      <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-600">
                          {{ $row->kategori }}
                        </span>
                      </td>
                      <td class="px-5 py-3.5 text-center whitespace-nowrap">
                        @if($row->tipe === 'pemasukan')
                          <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <i data-lucide="arrow-down-left" class="w-3 h-3"></i> Masuk
                          </span>
                        @else
                          <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            <i data-lucide="arrow-up-right" class="w-3 h-3"></i> Keluar
                          </span>
                        @endif
                      </td>
                      <td class="px-5 py-3.5 text-emerald-600 font-bold text-right whitespace-nowrap">
                        @if($row->masuk > 0)
                          + Rp {{ number_format($row->masuk, 0, ',', '.') }}
                        @else
                          <span class="text-slate-300">-</span>
                        @endif
                      </td>
                      <td class="px-5 py-3.5 text-rose-500 font-bold text-right whitespace-nowrap">
                        @if($row->keluar > 0)
                          - Rp {{ number_format($row->keluar, 0, ',', '.') }}
                        @else
                          <span class="text-slate-300">-</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center py-12 text-slate-400">
                        Tidak ada catatan transaksi pada periode yang dipilih.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
                @if($buku_kas->isNotEmpty())
                  <tfoot class="bg-slate-50 font-bold text-slate-800 border-t border-slate-200 text-sm">
                    <tr>
                      <td colspan="5" class="px-5 py-3 text-right uppercase tracking-wider text-xs text-slate-500">Total Periode Ini:</td>
                      <td class="px-5 py-3 text-right text-emerald-600 whitespace-nowrap">+ Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
                      <td class="px-5 py-3 text-right text-rose-500 whitespace-nowrap">- Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
                    </tr>
                  </tfoot>
                @endif
              </table>
            </div>
          </div>

          <!-- TAB 2: DETAIL PEMASUKAN -->
          <div x-show="activeTab === 'pemasukan'" class="p-0">
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead class="bg-brand-50/70 text-slate-500 text-xs uppercase tracking-wide">
                  <tr class="text-left border-b border-slate-100">
                    <th class="px-5 py-3.5 font-semibold">Kode Order</th>
                    <th class="px-5 py-3.5 font-semibold">Nama Pemesan</th>
                    <th class="px-5 py-3.5 font-semibold">Jenis Kertas & Lembar</th>
                    <th class="px-5 py-3.5 font-semibold">Metode Bayar</th>
                    <th class="px-5 py-3.5 font-semibold">Tanggal Order</th>
                    <th class="px-5 py-3.5 font-semibold text-right">Total Biaya</th>
                  </tr>
                </thead>
                <tbody id="pemasukanBody" class="divide-y divide-slate-100">
                  @forelse($pesanan as $row)
                    <tr class="hover:bg-slate-50/70 transition searchable-row">
                      <td class="px-5 py-3.5 font-bold text-slate-800">
                        {{ $row->kode_order }}
                      </td>
                      <td class="px-5 py-3.5 text-slate-700">
                        {{ $row->user->name ?? 'Pengguna' }}
                        <span class="text-xs text-slate-400 block">{{ $row->user->email ?? '-' }}</span>
                      </td>
                      <td class="px-5 py-3.5 text-slate-700">
                        <span class="font-medium">{{ $row->jenisKertas->nama_kertas ?? '-' }}</span>
                        <span class="text-xs text-slate-400 block">{{ $row->jumlah_lembar }} lembar</span>
                      </td>
                      <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold uppercase bg-slate-100 text-slate-700">
                          {{ $row->metode_pembayaran ?? 'Transfer' }}
                        </span>
                      </td>
                      <td class="px-5 py-3.5 text-slate-500 text-xs">
                        {{ $row->created_at->translatedFormat('d M Y, H:i') }}
                      </td>
                      <td class="px-5 py-3.5 text-emerald-600 font-bold text-right whitespace-nowrap">
                        + Rp {{ number_format($row->total_biaya, 0, ',', '.') }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center py-12 text-slate-400">
                        Tidak ada data pemasukan pada periode ini.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
                @if($pesanan->isNotEmpty())
                  <tfoot class="bg-slate-50 font-bold text-slate-800 border-t border-slate-200 text-sm">
                    <tr>
                      <td colspan="5" class="px-5 py-3 text-right uppercase tracking-wider text-xs text-slate-500">Subtotal Pemasukan:</td>
                      <td class="px-5 py-3 text-right text-emerald-600 whitespace-nowrap">+ Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
                    </tr>
                  </tfoot>
                @endif
              </table>
            </div>
          </div>

          <!-- TAB 3: DETAIL PENGELUARAN -->
          <div x-show="activeTab === 'pengeluaran'" class="p-0">
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead class="bg-brand-50/70 text-slate-500 text-xs uppercase tracking-wide">
                  <tr class="text-left border-b border-slate-100">
                    <th class="px-5 py-3.5 font-semibold">No / Ref</th>
                    <th class="px-5 py-3.5 font-semibold">Deskripsi Pengeluaran</th>
                    <th class="px-5 py-3.5 font-semibold">Kategori</th>
                    <th class="px-5 py-3.5 font-semibold">Tanggal Transaksi</th>
                    <th class="px-5 py-3.5 font-semibold text-right">Nominal</th>
                  </tr>
                </thead>
                <tbody id="pengeluaranBody" class="divide-y divide-slate-100">
                  @forelse($pengeluaran as $idx => $row)
                    <tr class="hover:bg-slate-50/70 transition searchable-row">
                      <td class="px-5 py-3.5 text-slate-400 text-xs font-mono">
                        EXP-{{ str_pad($row->id_pengeluaran, 4, '0', STR_PAD_LEFT) }}
                      </td>
                      <td class="px-5 py-3.5 font-medium text-slate-800">
                        {{ $row->deskripsi }}
                      </td>
                      <td class="px-5 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                          {{ $row->kategori }}
                        </span>
                      </td>
                      <td class="px-5 py-3.5 text-slate-500 text-xs">
                        {{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}
                      </td>
                      <td class="px-5 py-3.5 text-rose-500 font-bold text-right whitespace-nowrap">
                        - Rp {{ number_format($row->jumlah, 0, ',', '.') }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center py-12 text-slate-400">
                        Tidak ada data pengeluaran pada periode ini.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
                @if($pengeluaran->isNotEmpty())
                  <tfoot class="bg-slate-50 font-bold text-slate-800 border-t border-slate-200 text-sm">
                    <tr>
                      <td colspan="4" class="px-5 py-3 text-right uppercase tracking-wider text-xs text-slate-500">Subtotal Pengeluaran:</td>
                      <td class="px-5 py-3 text-right text-rose-500 whitespace-nowrap">- Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
                    </tr>
                  </tfoot>
                @endif
              </table>
            </div>
          </div>

          <!-- TAB 4: ANALISIS & RINGKASAN KATEGORI -->
          <div x-show="activeTab === 'analisis'" class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              
              <!-- Pemasukan Berdasarkan Jenis Kertas -->
              <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                  <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                      <i data-lucide="file-text" class="w-4 h-4"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Pemasukan per Jenis Kertas</h4>
                  </div>
                  <span class="text-xs text-emerald-600 font-bold">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</span>
                </div>

                <div class="space-y-3">
                  @forelse($breakdown_kertas as $item)
                    @php 
                      $percent = $total_pemasukan > 0 ? ($item->total / $total_pemasukan) * 100 : 0;
                    @endphp
                    <div>
                      <div class="flex justify-between text-xs mb-1">
                        <span class="font-semibold text-slate-700">{{ $item->nama_kertas }} <span class="text-slate-400 font-normal">({{ $item->lembar }} lembar, {{ $item->count }} pesanan)</span></span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($item->total, 0, ',', '.') }} ({{ number_format($percent, 1) }}%)</span>
                      </div>
                      <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                      </div>
                    </div>
                  @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada transaksi kertas pada periode ini.</p>
                  @endforelse
                </div>
              </div>

              <!-- Pengeluaran Berdasarkan Kategori -->
              <div class="bg-slate-50/70 border border-slate-200/80 rounded-xl p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                  <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center font-bold">
                      <i data-lucide="tag" class="w-4 h-4"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Pengeluaran per Kategori</h4>
                  </div>
                  <span class="text-xs text-rose-600 font-bold">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</span>
                </div>

                <div class="space-y-3">
                  @forelse($breakdown_pengeluaran as $item)
                    @php 
                      $percent = $total_pengeluaran > 0 ? ($item->total / $total_pengeluaran) * 100 : 0;
                    @endphp
                    <div>
                      <div class="flex justify-between text-xs mb-1">
                        <span class="font-semibold text-slate-700">{{ $item->kategori }} <span class="text-slate-400 font-normal">({{ $item->count }} kali transaksi)</span></span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($item->total, 0, ',', '.') }} ({{ number_format($percent, 1) }}%)</span>
                      </div>
                      <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                        <div class="bg-rose-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                      </div>
                    </div>
                  @empty
                    <p class="text-xs text-slate-400 py-4 text-center">Belum ada catatan pengeluaran pada periode ini.</p>
                  @endforelse
                </div>
              </div>

            </div>
          </div>

        </div>

      </main>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();

      // Client-side quick filter across tables
      const searchInput = document.getElementById('tableSearchInput');
      if (searchInput) {
        searchInput.addEventListener('input', function() {
          const query = this.value.toLowerCase();
          const rows = document.querySelectorAll('.searchable-row');
          rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
          });
        });
      }
    });
  </script>
</body>
</html>
