@include('layouts.header', ['title' => 'Dashboard - PrintLab'])

<body class="font-sans bg-brand-50 min-h-screen text-slate-900">

  <!-- NAVBAR -->
  <header class="sticky top-0 z-30 bg-white/90 backdrop-blur border-b border-slate-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">

      <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-[9px] bg-brand-600 flex items-center justify-center shrink-0">
          <svg viewBox="0 0 24 24" class="w-5 h-5 fill-white">
            <path d="M20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 18H4V6H20V18ZM6 10H8V14H6V10ZM9.5 8H11.5V16H9.5V8ZM13 11H15V14H13V11Z" />
          </svg>
        </div>
        <span class="text-lg font-bold text-slate-800 tracking-tight hidden xs:inline">PrintLab</span>
      </a>

      <nav class="hidden md:flex items-center gap-1">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-brand-700 bg-brand-50">Dashboard</a>
        <a href="{{ route('pesanan.create') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-500 hover:text-brand-700 hover:bg-brand-50 transition">Buat Pesanan</a>
      </nav>

      <div class="flex items-center gap-3">
        <span class="hidden sm:inline text-sm text-slate-500">Halo, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span></span>
        <form action="{{ route('logout') }}" method="POST" class="inline">
          @csrf
          <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-red-600 transition px-3 py-2 rounded-lg hover:bg-red-50">
            Keluar
          </button>
        </form>
      </div>
    </div>

    <!-- mobile nav -->
    <nav class="md:hidden flex border-t border-slate-100">
      <a href="{{ route('dashboard') }}" class="flex-1 text-center py-3 text-sm font-semibold text-brand-700 border-b-2 border-brand-600">Dashboard</a>
      <a href="{{ route('pesanan.create') }}" class="flex-1 text-center py-3 text-sm font-semibold text-slate-500 border-b-2 border-transparent">Buat Pesanan</a>
    </nav>
  </header>

  <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10">

    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight mb-8">
      Halo, {{ Auth::user()->name }} 👋
    </h2>

    <div class="grid sm:grid-cols-2 gap-5">

      <div class="bg-white rounded-2xl shadow-[0_4px_24px_rgba(30,60,160,0.06)] p-6 sm:p-7">
        <p class="text-sm text-slate-500 font-medium">Pesanan Disetujui</p>
        <h1 class="text-4xl sm:text-5xl font-bold text-green-600 mt-2">{{ $jumlah_selesai }}</h1>
      </div>

      <a href="{{ route('pesanan.create') }}"
        class="group flex items-center justify-center bg-brand-600 hover:bg-brand-700 rounded-2xl p-6 sm:p-7 shadow-lg shadow-brand-600/20 transition hover:-translate-y-0.5">
        <span class="flex items-center gap-2 text-white text-base sm:text-lg font-semibold">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          Buat Pesanan
        </span>
      </a>

    </div>

    <div class="bg-white rounded-2xl shadow-[0_4px_24px_rgba(30,60,160,0.06)] mt-6 p-6 sm:p-7">

      <h3 class="text-lg sm:text-xl font-bold mb-5">Pesanan Terakhir</h3>

      @if ($pesanan->isEmpty())

      <div class="text-center py-10 text-slate-400 text-sm">
        Belum ada pesanan. Mulai dengan membuat pesanan pertama Anda.
      </div>

      @else

      <!-- table: desktop / tablet -->
      <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-sm">
          <tbody>
            @foreach ($pesanan as $row)
            <tr class="border-b border-slate-100 last:border-0">
              <td class="p-3 font-medium text-slate-800">{{ $row->kode_order }}</td>
              <td class="p-3 text-slate-700">Rp{{ number_format($row->total_biaya, 0, ',', '.') }}</td>
              <td class="p-3">
                @if (in_array(strtolower($row->status), ['disetujui', 'selesai']))
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                  <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ ucfirst($row->status) }}
                </span>
                @elseif (strtolower($row->status) == 'ditolak')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                  <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600">
                  <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ ucfirst($row->status) }}
                </span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- card list: mobile -->
      <div class="sm:hidden space-y-3">
        @foreach ($pesanan as $row)
        <div class="border border-slate-100 rounded-xl p-4">
          <div class="flex items-center justify-between mb-2">
            <span class="font-semibold text-slate-800 text-sm">{{ $row->kode_order }}</span>
            @if (in_array(strtolower($row->status), ['disetujui', 'selesai']))
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
              <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ ucfirst($row->status) }}
            </span>
            @elseif (strtolower($row->status) == 'ditolak')
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">
              <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
            </span>
            @else
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600">
              <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ ucfirst($row->status) }}
            </span>
            @endif
          </div>
          <p class="text-sm text-slate-500">Total: <span class="text-slate-800 font-medium">Rp{{ number_format($row->total_biaya, 0, ',', '.') }}</span></p>
        </div>
        @endforeach
      </div>

      @endif

    </div>

  </main>

</body>

</html>