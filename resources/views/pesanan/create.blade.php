@include('layouts.header', ['title' => 'Buat Pesanan'])
<body class="font-sans bg-brand-50 min-h-screen text-slate-900">
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-slate-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">

      <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-[9px] bg-brand-600 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-5 h-5 fill-white">
            <path d="M20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 18H4V6H20V18ZM6 10H8V14H6V10ZM9.5 8H11.5V16H9.5V8ZM13 11H15V14H13V11Z"/>
          </svg>
        </div>
        <span class="text-lg font-bold text-slate-800 tracking-tight hidden xs:inline">PrintLab</span>
      </a>

      <nav class="hidden md:flex items-center gap-1">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-500 hover:text-brand-700 hover:bg-brand-50 transition">Dashboard</a>
        <a href="{{ route('pesanan.create') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-brand-700 bg-brand-50">Buat Pesanan</a>
      </nav>

      <div class="flex items-center gap-3">
        <span class="hidden sm:inline text-sm text-slate-500">Halo, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span></span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-red-600 transition px-3 py-2 rounded-lg hover:bg-red-50">
                Keluar
            </button>
        </form>
      </div>
    </div>

    <nav class="md:hidden flex border-t border-slate-100">
      <a href="{{ route('dashboard') }}" class="flex-1 text-center py-3 text-sm font-semibold text-slate-500 border-b-2 border-transparent">Dashboard</a>
      <a href="{{ route('pesanan.create') }}" class="flex-1 text-center py-3 text-sm font-semibold text-brand-700 border-b-2 border-brand-600">Buat Pesanan</a>
    </nav>
  </header>

  <main class="max-w-xl mx-auto px-4 sm:px-6 py-8 sm:py-10">

    <div class="bg-white rounded-2xl shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-6 sm:p-8">

      <h2 class="text-xl sm:text-2xl font-bold tracking-tight mb-7">Buat Pesanan</h2>

      <form action="{{ route('pesanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">

        <div>
          <label for="jumlah_lembar" class="block text-xs font-semibold uppercase tracking-wide text-slate-700 mb-1.5">
            Jumlah Lembar
          </label>
          <input
            type="number"
            id="jumlah_lembar"
            name="jumlah_lembar"
            min="1"
            oninput="hitungTotal()"
            required
            placeholder="Contoh: 20"
            class="w-full border-[1.5px] border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 bg-slate-50 placeholder:text-slate-400 outline-none transition focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-brand-600/10"
          >
        </div>

        <div>
            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-700 mb-2">
                Jenis Kertas
            </label>
            <div class="grid grid-cols-2 gap-3">
                @foreach ($jenisKertas as $kertas)
                <label class="relative flex flex-col gap-1 border-[1.5px] border-slate-200 rounded-xl px-4 py-3 cursor-pointer transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50">
                    <input 
                    type="radio" 
                    name="id_jenis_kertas" 
                    value="{{ $kertas->id_jenis_kertas }}" 
                    data-harga="{{ $kertas->harga }}" 
                    {{ $loop->first ? 'checked' : '' }} 
                    onchange="hitungTotal()" 
                    class="sr-only peer"
                    >
                    <span class="text-sm font-semibold text-slate-800">{{ $kertas->nama_kertas }}</span>
                    <span class="text-xs text-slate-500">Rp{{ number_format($kertas->harga, 0, ',', '.') }} / lembar</span>
                </label>
                @endforeach
            </div>
            @error('id_jenis_kertas')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>


        <hr class="border-slate-100">

        <div class="flex items-center justify-between bg-brand-50 rounded-xl px-5 py-4">
          <p class="text-sm text-slate-500 font-medium">Total</p>
          <h1 id="total_biaya" class="text-2xl sm:text-3xl font-bold text-brand-600">Rp0</h1>
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-700 mb-2">
            Metode Pembayaran
          </label>
          <div class="space-y-2.5">
            <label class="flex items-center gap-2.5 border-[1.5px] border-slate-200 rounded-xl px-4 py-3 cursor-pointer transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50">
              <input type="radio" name="metode_pembayaran" value="cash" checked class="accent-brand-600 w-4 h-4">
              <span class="text-sm font-medium text-slate-800">Cash</span>
            </label>
            <label class="flex items-center gap-2.5 border-[1.5px] border-slate-200 rounded-xl px-4 py-3 cursor-pointer transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50">
              <input type="radio" name="metode_pembayaran" value="cashless" class="accent-brand-600 w-4 h-4">
              <span class="text-sm font-medium text-slate-800">Cashless (QRIS / Transfer)</span>
            </label>
          </div>
        </div>

        <div>
          <label for="bukti" class="block text-xs font-semibold uppercase tracking-wide text-slate-700 mb-1.5">
            Upload Bukti Pembayaran
          </label>
          <input
            type="file"
            id="bukti"
            name="bukti_pembayaran"
            required
            accept="image/*,.pdf"
            class="w-full text-sm text-slate-500 border-[1.5px] border-dashed border-slate-200 rounded-xl px-4 py-3 bg-slate-50 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-600 file:text-white hover:file:bg-brand-700 cursor-pointer transition"
          >
        </div>

        <button
          type="submit"
          class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-xl py-3.5 shadow-lg shadow-brand-600/25 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-600/30 active:translate-y-0"
        >
          Pesan Sekarang
        </button>

      </form>

    </div>

  </main>
  
  <script>
    function hitungTotal() {
      // 1. Ambil jumlah lembar
      const lembar = parseInt(document.getElementById('jumlah_lembar').value) || 0;

      // 2. Ambil harga kertas dari radio button yang dipilih (menggunakan dataset data-harga)
      const selectedKertas = document.querySelector('input[name="id_jenis_kertas"]:checked');
      const harga = selectedKertas ? parseInt(selectedKertas.dataset.harga) : 0;

      // 3. Hitung total
      const total = lembar * harga;

      // 4. Tampilkan format Rupiah ke elemen total_biaya
      document.getElementById('total_biaya').innerText = 'Rp' + total.toLocaleString('id-ID');
    }

    // Jalankan sekali saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', hitungTotal);
  </script>
</body>
</html>