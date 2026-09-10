@include('layouts.header', ['title' => 'Kelola Pengeluaran - PrintLab'])

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
        <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 rounded-xl text-slate-600 hover:bg-slate-100 transition">
              <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h1 id="pageTitle" class="text-lg font-bold text-slate-800 tracking-tight">Kelola Pengeluaran</h1>
          </div>
          <div class="flex items-center gap-3">
            <span class="hidden sm:inline text-sm text-slate-500">Halo, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span></span>
          </div>
        </div>
      </header>

      <!-- MAIN CONTENT -->
      <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 w-full flex-1 space-y-6">

        <!-- HEADER TITLE -->
        <div>
          <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-800">Buku Kas Pengeluaran</h2>
          <p class="text-sm text-slate-500 mt-1">Catat dan pantau seluruh pengeluaran operasional percetakan.</p>
        </div>

        <!-- FLASH NOTIFIKASI -->
        @if(session('success'))
          <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2.5 shadow-sm">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
            <span class="font-medium">{{ session('success') }}</span>
          </div>
        @endif

        @if(session('error'))
          <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2.5 shadow-sm">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 shrink-0"></i>
            <span class="font-medium">{{ session('error') }}</span>
          </div>
        @endif

        @if($errors->any())
          <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm space-y-1 shadow-sm">
            <div class="flex items-center gap-2 font-semibold">
              <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 shrink-0"></i>
              <span>Mohon periksa kembali form pengeluaran:</span>
            </div>
            <ul class="list-disc list-inside pl-6 text-xs text-red-600">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <!-- 4 STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
          
          <!-- Card 1: Sisa Saldo Kas -->
          <div class="bg-brand-600 rounded-2xl shadow-lg shadow-brand-600/20 p-5 sm:p-6 transition hover:shadow-xl text-white">
            <div class="flex items-center justify-between">
              <p class="text-xs uppercase tracking-wider font-semibold text-brand-100">Saldo Kas Tersedia</p>
              <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white">
                <i data-lucide="wallet" class="w-5 h-5"></i>
              </div>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold mt-2">Rp {{ number_format($saldo_kas ?? 0, 0, ',', '.') }}</h3>
            <p class="text-xs text-brand-100/90 mt-1">Batas maksimal pengeluaran</p>
          </div>

          <!-- Card 2: Total Pengeluaran -->
          <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-5 sm:p-6 transition hover:shadow-md">
            <div class="flex items-center justify-between">
              <p class="text-xs uppercase tracking-wider font-semibold text-slate-400">Total Pengeluaran</p>
              <div class="w-9 h-9 rounded-xl bg-red-50 text-red-500 flex items-center justify-center">
                <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
              </div>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-red-500 mt-2">Rp {{ number_format($total_pengeluaran ?? 0, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-400 mt-1">Akumulasi seluruh pengeluaran</p>
          </div>

          <!-- Card 3: Pengeluaran Bulan Ini -->
          <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-5 sm:p-6 transition hover:shadow-md">
            <div class="flex items-center justify-between">
              <p class="text-xs uppercase tracking-wider font-semibold text-slate-400">Bulan Ini ({{ date('M Y') }})</p>
              <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="calendar" class="w-5 h-5"></i>
              </div>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-slate-800 mt-2">Rp {{ number_format($pengeluaran_bulan_ini ?? 0, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-400 mt-1">Pengeluaran berjalan bulan ini</p>
          </div>

          <!-- Card 4: Total Transaksi -->
          <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-5 sm:p-6 transition hover:shadow-md">
            <div class="flex items-center justify-between">
              <p class="text-xs uppercase tracking-wider font-semibold text-slate-400">Total Transaksi</p>
              <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-lucide="receipt" class="w-5 h-5"></i>
              </div>
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-slate-800 mt-2">{{ number_format($total_transaksi ?? 0) }} <span class="text-sm font-normal text-slate-400">Catatan</span></h3>
            <p class="text-xs text-slate-400 mt-1">Jumlah riwayat pengeluaran</p>
          </div>

        </div>

        <!-- FORM TAMBAH PENGELUARAN -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] overflow-hidden">
          <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
            <div class="flex items-center gap-2.5">
              <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center font-bold">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
              </div>
              <h3 class="font-bold text-slate-800 text-base">Tambah Pengeluaran Baru</h3>
            </div>
            <span class="text-xs text-slate-400 hidden sm:inline">* Wajib diisi semua</span>
          </div>

          <form action="{{ route('admin.pengeluaran.store') }}" method="POST" class="p-5 sm:p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              
              <!-- Deskripsi Pengeluaran -->
              <div>
                <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                  Deskripsi Pengeluaran <span class="text-red-500">*</span>
                </label>
                <input 
                  type="text" 
                  id="deskripsi" 
                  name="deskripsi" 
                  value="{{ old('deskripsi') }}" 
                  placeholder="Contoh: Beli Tinta Hitam Epson L3110" 
                  class="w-full px-4 py-2.5 text-sm bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-100 transition text-slate-800" 
                  required>
              </div>

              <!-- Kategori Pengeluaran -->
              <div>
                <label for="kategori" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                  Kategori <span class="text-red-500">*</span>
                </label>
                <input 
                  type="text" 
                  id="kategori" 
                  name="kategori" 
                  value="{{ old('kategori') }}" 
                  list="kategoriList" 
                  placeholder="Pilih atau ketik kategori..." 
                  class="w-full px-4 py-2.5 text-sm bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-100 transition text-slate-800" 
                  required>
                <datalist id="kategoriList">
                  <option value="Tinta & Toner">
                  <option value="Kertas & Bahan">
                  <option value="Maintenace Lab">
                  <option value="Lain-lain">
                </datalist>

                <!-- Quick Tags Kategori -->
                <div class="flex flex-wrap items-center gap-1.5 mt-2">
                  <span class="text-xs text-slate-400 mr-1">Pilihan cepat:</span>
                  <button type="button" onclick="setKategori('Tinta & Toner')" class="text-xs px-2.5 py-1 bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-600 rounded-lg transition font-medium">Tinta</button>
                  <button type="button" onclick="setKategori('Kertas & Bahan')" class="text-xs px-2.5 py-1 bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-600 rounded-lg transition font-medium">Kertas</button>
                  <button type="button" onclick="setKategori('Maintenace Lab')" class="text-xs px-2.5 py-1 bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-600 rounded-lg transition font-medium">Maintenance</button>
                </div>
              </div>

              <!-- Nominal Pengeluaran -->
              <div>
                <div class="flex items-center justify-between mb-2">
                  <label for="jumlah" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Nominal (Rp) <span class="text-red-500">*</span>
                  </label>
                  <span class="text-xs text-slate-500 font-medium">Saldo Tersedia: <span class="font-bold text-slate-700">Rp {{ number_format($saldo_kas ?? 0, 0, ',', '.') }}</span></span>
                </div>
                <input 
                  type="number" 
                  id="jumlah" 
                  name="jumlah" 
                  value="{{ old('jumlah') }}" 
                  placeholder="Contoh: 50000" 
                  min="1" 
                  class="w-full px-4 py-2.5 text-sm bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-100 transition text-slate-800 font-semibold" 
                  required>
                <div id="saldoWarning" class="hidden mt-2 p-2.5 rounded-lg bg-red-50 border border-red-200 text-red-600 text-xs font-medium flex items-center gap-2">
                  <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 text-red-600"></i>
                  <span>Peringatan: Nominal melebihi saldo kas yang tersedia (Rp {{ number_format($saldo_kas ?? 0, 0, ',', '.') }})!</span>
                </div>
              </div>

              <!-- Tanggal Pengeluaran -->
              <div>
                <label for="tanggal" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                  Tanggal Transaksi <span class="text-red-500">*</span>
                </label>
                <input 
                  type="date" 
                  id="tanggal" 
                  name="tanggal" 
                  value="{{ old('tanggal', date('Y-m-d')) }}" 
                  class="w-full px-4 py-2.5 text-sm bg-white border border-slate-300 rounded-xl focus:outline-none focus:border-brand-600 focus:ring-2 focus:ring-brand-100 transition text-slate-800" 
                  required>
              </div>

            </div>

            <!-- Submit Action -->
            <div class="flex justify-end pt-2">
              <button 
                type="submit" 
                id="submitBtn"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-2.5 rounded-xl shadow-md shadow-brand-600/20 hover:shadow-lg transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Simpan Pengeluaran</span>
              </button>
            </div>
          </form>
        </div>

        <!-- TABLE DAFTAR RIWAYAT PENGELUARAN -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_rgba(30,60,160,0.06)] overflow-hidden">
          
          <!-- TABLE TOPBAR & SEARCH -->
          <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <h3 class="font-bold text-slate-800 text-base">Riwayat Pengeluaran</h3>
              <p class="text-xs text-slate-400 mt-0.5">Daftar transaksi kas yang telah dikeluarkan</p>
            </div>
            <div class="relative">
              <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
              <input 
                type="text" 
                id="pengeluaranSearch" 
                placeholder="Cari deskripsi / kategori..." 
                class="text-sm pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-600 w-full sm:w-64 transition">
            </div>
          </div>

          <!-- TABLE -->
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-brand-50/70 text-slate-500 text-xs uppercase tracking-wide">
                <tr class="text-left border-b border-slate-100">
                  <th class="px-5 py-3.5 font-semibold">Deskripsi</th>
                  <th class="px-5 py-3.5 font-semibold">Kategori</th>
                  <th class="px-5 py-3.5 font-semibold">Tanggal</th>
                  <th class="px-5 py-3.5 font-semibold text-right">Jumlah</th>
                </tr>
              </thead>
              <tbody id="pengeluaranTableBody" class="divide-y divide-slate-100">
                @forelse($pengeluaran as $row)
                  <tr class="hover:bg-slate-50/70 transition">
                    <td class="px-5 py-3.5 font-semibold text-slate-800">
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
                    <td class="px-5 py-3.5 text-red-500 font-bold text-right whitespace-nowrap">
                      - Rp {{ number_format($row->jumlah, 0, ',', '.') }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center py-10 text-slate-400">
                      Belum ada data pengeluaran yang tercatat.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- PAGINATION -->
          @if($pengeluaran->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
              {{ $pengeluaran->links() }}
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

      // Client-side quick filter
      const searchInput = document.getElementById('pengeluaranSearch');
      const tableBody = document.getElementById('pengeluaranTableBody');
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

      // Realtime validation saldo kas
      const maxSaldo = {{ $saldo_kas ?? 0 }};
      const jumlahInput = document.getElementById('jumlah');
      const saldoWarning = document.getElementById('saldoWarning');
      const submitBtn = document.getElementById('submitBtn');

      if (jumlahInput && saldoWarning) {
        jumlahInput.addEventListener('input', function() {
          const val = parseFloat(this.value) || 0;
          if (val > maxSaldo) {
            saldoWarning.classList.remove('hidden');
            this.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-100');
            this.classList.remove('border-slate-300', 'focus:border-brand-600', 'focus:ring-brand-100');
          } else {
            saldoWarning.classList.add('hidden');
            this.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-100');
            this.classList.add('border-slate-300', 'focus:border-brand-600', 'focus:ring-brand-100');
          }
        });
      }
    });

    // Quick fill kategori
    function setKategori(nama) {
      const inputKategori = document.getElementById('kategori');
      if (inputKategori) {
        inputKategori.value = nama;
        inputKategori.focus();
      }
    }
  </script>
</body>
</html>
