@include('layouts.header', ['title' => 'Verifikasi Pesanan - PrintLab'])

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

    <div class="flex-1 flex flex-col min-w-0 min-h-screen">

      <header class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-slate-100">
        <div class="px-4 sm:px-6 h-16 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 rounded-xl text-slate-600 hover:bg-slate-100">
              <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h1 id="pageTitle" class="text-lg font-bold text-slate-800 tracking-tight">Verifikasi Pesanan</h1>
          </div>
          <div class="flex items-center gap-3">
            <span class="hidden sm:inline text-sm text-slate-500">Halo, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span></span>
          </div>
        </div>
      </header>

      <main class="flex-1 overflow-y-auto p-6 space-y-6">
        
        <!-- FLASH MESSAGE -->
        @if(session('success'))
          <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
            <span>{{ session('success') }}</span>
          </div>
        @endif
        @if(session('error'))
          <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4 text-red-600 shrink-0"></i>
            <span>{{ session('error') }}</span>
          </div>
        @endif

        <!-- STAT CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
              <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
            <div>
              <p class="text-xs text-slate-400 font-medium">Menunggu Verifikasi</p>
              <p class="text-xl font-bold text-slate-800">{{ $jumlah_verifikasi }}</p>
            </div>
          </div>
          <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
              <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            </div>
            <div>
              <p class="text-xs text-slate-400 font-medium">Disetujui Hari Ini</p>
              <p class="text-xl font-bold text-slate-800">{{ $jumlah_selesai }}</p>
            </div>
          </div>
          <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
              <i data-lucide="x-circle" class="w-5 h-5"></i>
            </div>
            <div>
              <p class="text-xs text-slate-400 font-medium">Ditolak Hari Ini</p>
              <p class="text-xl font-bold text-slate-800">{{ $jumlah_ditolak }}</p>
            </div>
          </div>
        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
          <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
            <h3 class="font-bold text-slate-800 text-sm">Daftar Pengajuan</h3>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100 bg-slate-50/50">
                  <th class="px-5 py-3 font-semibold">Nama Pemesan</th>
                  <th class="px-5 py-3 font-semibold">Kode Order</th>
                  <th class="px-5 py-3 font-semibold">Jenis Kertas</th>
                  <th class="px-5 py-3 font-semibold">Jumlah Lembar</th>
                  <th class="px-5 py-3 font-semibold">Total</th>
                  <th class="px-5 py-3 font-semibold">Pembayaran</th>
                  <th class="px-5 py-3 font-semibold">Bukti</th>
                  <th class="px-5 py-3 font-semibold">Status</th>
                  <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
              @forelse($pesanan as $row)
                <tr class="hover:bg-slate-50/60 transition">
                  <td class="px-5 py-3.5">
                    <p class="font-semibold text-slate-800">{{ $row->user->name ?? '-' }}</p>
                  </td>

                  <td class="px-5 py-3.5 text-slate-500 font-medium">{{ $row->kode_order }}</td>

                  <td class="px-5 py-3.5 text-slate-500">{{ $row->jenisKertas->nama_kertas ?? '-' }}</td>

                  <td class="px-5 py-3.5 text-slate-500">{{ $row->jumlah_lembar }} lbr</td>

                  <td class="px-5 py-3.5 text-slate-700 font-semibold">Rp{{ number_format($row->total_biaya, 0, ',', '.') }}</td>

                  <td class="px-5 py-3.5 text-slate-500 capitalize">{{ $row->metode_pembayaran }}</td>

                  <td class="px-5 py-3.5">
                    @if(!empty($row->bukti_pembayaran))
                      <button onclick="openModal('{{ asset('storage/' . $row->bukti_pembayaran) }}', 'Bukti Pembayaran - {{ $row->kode_order }}')" class="text-brand-600 font-semibold hover:underline flex items-center gap-1">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>Lihat Bukti
                      </button>
                    @else 
                      <span class="text-slate-400 text-xs">Tidak ada</span>
                    @endif
                  </td>       
                  
                  <td class="px-5 py-3.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold 
                      @if($row->status === 'disetujui' || $row->status === 'selesai') bg-emerald-50 text-emerald-700
                      @elseif($row->status === 'ditolak') bg-red-50 text-red-700
                      @else bg-amber-50 text-amber-700 @endif">
                      <span class="w-1.5 h-1.5 rounded-full 
                        @if($row->status === 'disetujui' || $row->status === 'selesai') bg-emerald-500
                        @elseif($row->status === 'ditolak') bg-red-500
                        @else bg-amber-500 @endif"></span>
                      {{ ucfirst($row->status) }}
                    </span>
                  </td>

                  <td class="px-5 py-3.5">
                    <form action="{{ route('admin.verifikasi.update') }}" method="POST" class="flex items-center justify-end gap-2">
                        @csrf
                        <input type="hidden" name="kode_order" value="{{ $row->kode_order }}">
                        <button type="submit" name="status" value="disetujui" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center transition" title="Setujui">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </button>
                        <button type="submit" name="status" value="ditolak" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" title="Tolak">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="py-8 text-center text-slate-400">
                    Tidak ada pengajuan pesanan yang perlu diverifikasi.
                  </td>
                </tr>
              @endforelse
              </tbody>
            </table>
          </div>

          <!-- PAGINATION -->
          <div class="px-5 py-4 border-t border-slate-100">
            {{ $pesanan->links() }}
          </div>
        </div>

      </main>
    </div>
  </div>

  <!-- MODAL: PREVIEW DOKUMEN -->
  <div id="docModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-slate-900/50 backdrop-blur-sm px-4">
    <div class="bg-white rounded-xl w-full max-w-md overflow-hidden shadow-2xl">
      <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 id="modalTitle" class="font-bold text-slate-800 text-sm">Pratinjau Dokumen</h3>
        <button onclick="closeModal('docModal')" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
      </div>
      <div class="p-5">
        <img id="docPreviewImage" src="" alt="Pratinjau Bukti Pembayaran" class="w-full h-auto aspect-[4/3] object-contain bg-slate-100 rounded-lg">
        <p class="text-xs text-slate-400 mt-3 text-center">Pratinjau dokumen yang diunggah pengguna</p>
      </div>
      <div class="px-5 py-4 border-t border-slate-100 flex gap-2">
        <button onclick="closeModal('docModal')" class="flex-1 py-2 rounded-lg border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition">Tutup</button>
        <a id="docDownloadLink" href="#" target="_blank" class="flex-1 py-2 rounded-lg bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition text-center">Buka Tab Baru</a>
      </div>
    </div>
  </div>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();
    });

    function closeModal(id) {
      const modal = document.getElementById(id);
      modal.classList.add('hidden');
      modal.classList.remove('flex');

      if (id === 'docModal') {
        document.getElementById('docPreviewImage').src = '';
      }
    }

    function openModal(imgSrc, title) {
      const modal = document.getElementById('docModal');
      const modalTitle = document.getElementById('modalTitle');
      const previewImg = document.getElementById('docPreviewImage');
      const downloadLink = document.getElementById('docDownloadLink');

      modalTitle.textContent = title;
      previewImg.src = imgSrc;
      downloadLink.href = imgSrc;

      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }
  </script>
</body>
</html>
