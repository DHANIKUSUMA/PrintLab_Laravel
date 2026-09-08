@include('layouts.header', ['title' => 'Dashboard Admin - PrintLab'])

<body class="font-sans bg-brand-50 min-h-screen text-slate-900 antialiased">

  <!-- APP CONTAINER -->
  <div class="min-h-screen bg-brand-50 flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Backdrop Overlay (Hanya di layar HP saat sidebar dibuka) -->
    <div 
      x-show="sidebarOpen" 
      x-cloak
      x-transition:enter="transition-opacity ease-linear duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition-opacity ease-linear duration-300"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      @click="sidebarOpen = false" 
      class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden"
    ></div>

    <!-- SIDEBAR (Full height atas-bawah di laptop, hidden drawer di HP) -->
    @include('layouts.sidebar')

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 min-w-0 flex flex-col min-h-screen">

      <!-- TOPBAR / NAVBAR ATAS -->
      <header class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-slate-100">
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <!-- Tombol Hamburger Menu (Hanya tampil di Layar HP/Tablet) -->
            <button 
              @click="sidebarOpen = true" 
              class="lg:hidden p-2 -ml-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none transition"
              aria-label="Buka Menu"
            >
              <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <h1 id="pageTitle" class="text-lg font-bold text-slate-800 tracking-tight">Dashboard</h1>
          </div>
          <div class="flex items-center gap-3">
            <span class="hidden sm:inline text-sm text-slate-500">Halo, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span></span>
          </div>
        </div>
      </header>

      <!-- ISI KONTEN UTAMA -->
      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 w-full flex-1">

        <!-- ============ DASHBOARD STATS ============ -->
        <section id="dashboard" class="page active">
          <div class="mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-800">Halo, Admin 👋</h2>
            <p class="text-sm text-slate-500 mt-1">Berikut ringkasan statistik dan aktivitas percetakan hari ini.</p>
          </div>

          <!-- STATS CARDS -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-5 sm:p-6 transition hover:shadow-md">
              <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500 font-medium">Order Hari Ini</p>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                  <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                </div>
              </div>
              <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mt-2">{{ $jumlah_print }}</h1>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-5 sm:p-6 transition hover:shadow-md">
              <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500 font-medium">Pendapatan Hari Ini</p>
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                  <i data-lucide="arrow-down-left" class="w-4 h-4"></i>
                </div>
              </div>
              <h1 class="text-2xl sm:text-3xl font-bold text-green-600 mt-2">Rp{{ number_format($pendapatan_harian, 0, ',', '.') }}</h1>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-5 sm:p-6 transition hover:shadow-md">
              <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500 font-medium">Pengeluaran</p>
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                  <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                </div>
              </div>
              <h1 class="text-2xl sm:text-3xl font-bold text-red-500 mt-2">Rp{{ number_format($total_pengeluaran, 0, ',', '.') }}</h1>
            </div>

            <div class="bg-brand-600 rounded-2xl shadow-lg shadow-brand-600/20 p-5 sm:p-6 transition hover:shadow-xl text-white">
              <div class="flex items-center justify-between">
                <p class="text-sm text-brand-100 font-medium">Saldo Kas</p>
                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white">
                  <i data-lucide="wallet" class="w-4 h-4"></i>
                </div>
              </div>
              <h1 class="text-2xl sm:text-3xl font-bold mt-2">Rp{{ number_format($saldo_kas, 0, ',', '.') }}</h1>
            </div>
          </div>

          <!-- RECENT ORDERS TABLE -->
          <div class="mt-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-4 sm:p-6 lg:p-7">
              <div class="flex items-center justify-between mb-5">
                <div>
                  <h3 class="text-lg sm:text-xl font-bold text-slate-800">Order Terbaru</h3>
                  <p class="text-xs text-slate-400 mt-0.5">Daftar transaksi cetak yang baru masuk.</p>
                </div>
                <a href="{{ url('/admin/KelolaPesanan') }}" class="text-xs sm:text-sm font-semibold text-brand-600 hover:text-brand-700 hover:underline inline-flex items-center gap-1">
                  Lihat semua <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
              </div>

              <!-- TAMPILAN DESKTOP & TABLET (Table View) -->
              <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left">
                  <thead>
                    <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                      <th class="pb-3 px-3 whitespace-nowrap">Kode Order</th>
                      <th class="pb-3 px-3 whitespace-nowrap">Nama Pemesan</th>
                      <th class="pb-3 px-3 whitespace-nowrap">Jenis Kertas</th>
                      <th class="pb-3 px-3 whitespace-nowrap">Status</th>
                      <th class="pb-3 px-3 whitespace-nowrap text-right">Total Biaya</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    @forelse($pesanan as $row)
                      <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-3 font-semibold text-slate-800 whitespace-nowrap">{{ $row->kode_order }}</td>
                        <td class="py-3.5 px-3 text-slate-600 whitespace-nowrap">{{ $row->user->name ?? '-' }}</td>
                        <td class="py-3.5 px-3 text-slate-600 whitespace-nowrap">{{ $row->jenisKertas->nama_kertas ?? '-' }} ({{ $row->jumlah_lembar }} lbr)</td>
                        <td class="py-3.5 px-3 whitespace-nowrap">
                          <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold 
                            @if($row->status === 'selesai') bg-green-50 text-green-700
                            @elseif($row->status === 'disetujui') bg-blue-50 text-blue-700
                            @elseif($row->status === 'ditolak') bg-red-50 text-red-700
                            @else bg-amber-50 text-amber-700 @endif">
                            <span class="w-1.5 h-1.5 rounded-full 
                              @if($row->status === 'selesai') bg-green-500
                              @elseif($row->status === 'disetujui') bg-blue-500
                              @elseif($row->status === 'ditolak') bg-red-500
                              @else bg-amber-500 @endif"></span>
                            {{ ucfirst($row->status) }}
                          </span>
                        </td>
                        <td class="py-3.5 px-3 text-right text-slate-800 font-semibold whitespace-nowrap">Rp{{ number_format($row->total_biaya, 0, ',', '.') }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">Belum ada data pesanan yang masuk.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              <!-- TAMPILAN MOBILE / HP (Card List View) -->
              <div class="md:hidden space-y-3">
                @forelse($pesanan as $row)
                  <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition flex flex-col gap-2.5">
                    <div class="flex items-center justify-between gap-2">
                      <span class="font-bold text-slate-800 text-sm tracking-tight">{{ $row->kode_order }}</span>
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold 
                        @if($row->status === 'selesai') bg-green-50 text-green-700 border border-green-200/50
                        @elseif($row->status === 'disetujui') bg-blue-50 text-blue-700 border border-blue-200/50
                        @elseif($row->status === 'ditolak') bg-red-50 text-red-700 border border-red-200/50
                        @else bg-amber-50 text-amber-700 border border-amber-200/50 @endif">
                        <span class="w-1.5 h-1.5 rounded-full 
                          @if($row->status === 'selesai') bg-green-500
                          @elseif($row->status === 'disetujui') bg-blue-500
                          @elseif($row->status === 'ditolak') bg-red-500
                          @else bg-amber-500 @endif"></span>
                        {{ ucfirst($row->status) }}
                      </span>
                    </div>

                    <div class="text-xs text-slate-600 space-y-1.5">
                      <div class="flex items-center justify-between">
                        <span class="text-slate-400">Pemesan</span>
                        <span class="font-medium text-slate-700">{{ $row->user->name ?? '-' }}</span>
                      </div>
                      <div class="flex items-center justify-between">
                        <span class="text-slate-400">Kertas</span>
                        <span class="font-medium text-slate-700">{{ $row->jenisKertas->nama_kertas ?? '-' }} ({{ $row->jumlah_lembar }} lbr)</span>
                      </div>
                    </div>

                    <div class="pt-2.5 border-t border-slate-200/60 flex items-center justify-between">
                      <span class="text-xs text-slate-400 font-medium">Total Biaya</span>
                      <span class="text-sm font-bold text-slate-800">Rp{{ number_format($row->total_biaya, 0, ',', '.') }}</span>
                    </div>
                  </div>
                @empty
                  <div class="py-8 text-center text-slate-400 text-sm">
                    Belum ada data pesanan yang masuk.
                  </div>
                @endforelse
              </div>

            </div>
          </div>
        </section>

      </main>
    </div>
  </div>

  <!-- Lucide Icons CDN -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();
    });
  </script>
</body>
</html>
