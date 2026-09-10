@include('layouts.header', ['title' => 'Kelola Pesanan - PrintLab'])

<body class="font-sans bg-brand-50 min-h-screen text-slate-900 antialiased">

  <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
    
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
        <div class="px-4 sm:px-6 h-16 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 rounded-xl text-slate-600 hover:bg-slate-100">
              <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h1 id="pageTitle" class="text-lg font-bold text-slate-800 tracking-tight">Kelola Pesanan</h1>
          </div>
          <div class="flex items-center gap-3">
            <span class="hidden sm:inline text-sm text-slate-500">Halo, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span></span>
          </div>
        </div>
      </header>

      <!-- MAIN CONTENT -->
      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 w-full flex-1">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-5 sm:p-7">

          <!-- HEADER TITLE -->
          <div class="mb-6">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-800">Semua Pesanan</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar seluruh transaksi dan pesanan cetak pelanggan.</p>
          </div>

          <!-- FORM FILTER & PENCARIAN -->
          <form method="GET" action="{{ route('admin.kelola-pesanan') }}" class="mb-6 flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
            
            <!-- TAB FILTER STATUS -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-2 md:pb-0 scrollbar-none">
              <a href="{{ route('admin.kelola-pesanan', array_merge(request()->except(['status', 'page']))) }}"
                 class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ !request()->filled('status') ? 'bg-brand-600 text-white shadow-sm shadow-brand-600/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
              </a>
              <a href="{{ route('admin.kelola-pesanan', array_merge(request()->except('page'), ['status' => 'menunggu'])) }}"
                 class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request('status') === 'menunggu' ? 'bg-amber-500 text-white shadow-sm shadow-amber-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Menunggu
              </a>
              <a href="{{ route('admin.kelola-pesanan', array_merge(request()->except('page'), ['status' => 'disetujui'])) }}"
                 class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request('status') === 'disetujui' ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Disetujui
              </a>
              <a href="{{ route('admin.kelola-pesanan', array_merge(request()->except('page'), ['status' => 'ditolak'])) }}"
                 class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request('status') === 'ditolak' ? 'bg-red-500 text-white shadow-sm shadow-red-500/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Ditolak
              </a>
              <a href="{{ route('admin.kelola-pesanan', array_merge(request()->except('page'), ['status' => 'selesai'])) }}"
                 class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request('status') === 'selesai' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Selesai
              </a>
            </div>

            <!-- SEARCH INPUT -->
            <div class="flex items-center gap-2">
              @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
              @endif
              <div class="relative flex-1 sm:w-64">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / nama pemesan..." 
                       class="w-full text-sm pl-9 pr-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-600 transition">
              </div>
              <button type="submit" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                Cari
              </button>
              @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('admin.kelola-pesanan') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition" title="Reset Filter">
                  <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
              @endif
            </div>

          </form>
            
          <!-- TABEL PESANAN -->
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-brand-50/70 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                  <th class="text-left px-5 py-3 font-semibold">Kode</th>
                  <th class="text-left px-5 py-3 font-semibold">Nama Pemesan</th>
                  <th class="text-left px-5 py-3 font-semibold">Jenis Kertas</th>
                  <th class="text-left px-5 py-3 font-semibold">Status</th>
                  <th class="text-right px-5 py-3 font-semibold">Total Biaya</th>
                </tr>
              </thead>
              <tbody id="pesananTableBody" class="divide-y divide-slate-100">
                @forelse($pesanan as $row)
                  <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $row->kode_order }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $row->user->name ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $row->jenisKertas->nama_kertas ?? '-' }} ({{ $row->jumlah_lembar }} lbr)</td>
                    <td class="px-5 py-3.5">
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold 
                        @if($row->status === 'selesai') bg-emerald-50 text-emerald-700
                        @elseif($row->status === 'disetujui') bg-blue-50 text-blue-700
                        @elseif($row->status === 'ditolak') bg-red-50 text-red-700
                        @else bg-amber-50 text-amber-700 @endif">
                        <span class="w-1.5 h-1.5 rounded-full 
                          @if($row->status === 'selesai') bg-emerald-500
                          @elseif($row->status === 'disetujui') bg-blue-500
                          @elseif($row->status === 'ditolak') bg-red-500
                          @else bg-amber-500 @endif"></span>
                        {{ ucfirst($row->status) }}
                      </span>
                    </td>
                    <td class="px-5 py-3.5 text-right font-semibold text-slate-800">
                      Rp{{ number_format($row->total_biaya, 0, ',', '.') }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="py-12 text-center text-slate-400">
                      <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                      Tidak ada data pesanan yang sesuai dengan filter/pencarian.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- PAGINATION -->
          @if($pesanan->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-100">
              {{ $pesanan->links() }}
            </div>
          @endif

        </div>
      </main>

    </div>
  </div>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();
    });
  </script>
</body>
</html>
