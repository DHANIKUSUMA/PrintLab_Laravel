@include('layouts.header', ['title' => 'Laporan Pemasukan - PrintLab'])

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
            <h1 id="pageTitle" class="text-lg font-bold text-slate-800 tracking-tight">Pemasukan</h1>
          </div>
          <div class="flex items-center gap-3">
            <span class="hidden sm:inline text-sm text-slate-500">Halo, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span></span>
          </div>
        </div>
      </header>

      <!-- MAIN CONTENT -->
      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 w-full flex-1 space-y-6">

        <!-- CARD SALDO KAS -->
        <div class="bg-brand-600 rounded-2xl shadow-lg shadow-brand-600/20 p-6 sm:p-7">
          <p class="text-sm text-brand-100 font-medium">Saldo Kas (Total Pemasukan)</p>
          <h1 class="text-3xl sm:text-4xl font-bold text-white mt-2">Rp {{ number_format($saldo_kas, 0, ',', '.') }}</h1>
        </div>

        <!-- TABLE PEMASUKAN -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] overflow-hidden">
          <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h3 class="font-bold text-slate-800 text-base">Daftar Pemasukan</h3>
            <div class="relative">
              <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
              <input type="text" id="pemasukanSearch" placeholder="Cari kode order / nama..." class="text-sm pl-9 pr-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-600 w-full sm:w-64 transition">
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-brand-50/70 text-slate-500 text-xs uppercase tracking-wide">
                <tr class="text-left border-b border-slate-100">
                  <th class="px-5 py-3 font-semibold">Keterangan</th>
                  <th class="px-5 py-3 font-semibold">Nama Pemesan</th>
                  <th class="px-5 py-3 font-semibold">Tanggal</th>
                  <th class="px-5 py-3 font-semibold text-right">Jumlah</th>
                </tr>
              </thead>
              <tbody id="pemasukanTableBody" class="divide-y divide-slate-100">
                @forelse($pesanan as $row)
                  <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-5 py-3.5 font-semibold text-slate-800">
                      {{ $row->kode_order }} 
                      <span class="text-xs font-normal text-slate-400 block">{{ $row->jenisKertas->nama_kertas ?? '-' }} ({{ $row->jumlah_lembar }} lembar)</span>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $row->user->name ?? 'User' }}</td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $row->created_at->format('d M Y, H:i') }}</td>
                    <td class="px-5 py-3.5 text-emerald-600 font-bold text-right">+ Rp {{ number_format($row->total_biaya, 0, ',', '.') }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center py-10 text-slate-400">Belum ada data pemasukan.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- PAGINATION -->
          @if($pesanan->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
              {{ $pesanan->links() }}
            </div>
          @endif
        </div>

      </main>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();

      // Client-side search
      const searchInput = document.getElementById('pemasukanSearch');
      const tableBody = document.getElementById('pemasukanTableBody');
      if (searchInput && tableBody) {
        searchInput.addEventListener('input', function() {
          const query = this.value.toLowerCase();
          const rows = tableBody.querySelectorAll('tr');
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
