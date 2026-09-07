<!-- SIDEBAR -->
<aside 
  :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
  class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200/80 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:inset-auto lg:min-h-screen lg:h-auto shrink-0 shadow-2xl lg:shadow-none"
>
  <!-- LOGO & BRAND HEADER -->
  <div class="h-16 flex items-center justify-between px-5 border-b border-slate-100 shrink-0">
    <div class="flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl bg-brand-600 flex items-center justify-center shrink-0 shadow-md shadow-brand-600/20">
        <svg viewBox="0 0 24 24" class="w-5 h-5 fill-white">
          <path d="M20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 18H4V6H20V18ZM6 10H8V14H6V10ZM9.5 8H11.5V16H9.5V8ZM13 11H15V14H13V11Z"/>
        </svg>
      </div>
      <div>
        <span class="text-base font-bold text-slate-800 tracking-tight leading-none block">PrintLab</span>
        <span class="text-xs text-slate-400 font-medium leading-none">Panel Admin</span>
      </div>
    </div>
    <!-- Tombol Tutup (Hanya Tampil di Mobile) -->
    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 transition">
      <i data-lucide="x" class="w-5 h-5"></i>
    </button>
  </div>

  <!-- NAVIGATION LINKS -->
  <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-1 text-sm">
    <p class="px-3 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">Operasional</p>
    
    
    <a href="{{ url('/admin/dashboard') }}" 
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->is('admin/dashboard') ? 'text-brand-700 bg-brand-50 shadow-sm' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/70' }}">
      <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->is('admin/dashboard') ? 'text-brand-600' : 'text-slate-400' }}"></i>
      Dashboard
    </a>
    
    <a href="{{ url('/admin/KelolaPesanan') }}" 
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->is('admin/KelolaPesanan*') ? 'text-brand-700 bg-brand-50 shadow-sm' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/70' }}">
      <i data-lucide="clipboard-list" class="w-4 h-4 {{ request()->is('admin/KelolaPesanan*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
      Kelola Pesanan
    </a>

    <a href="{{ url('/admin/Verifikasi') }}" 
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->is('admin/Verifikasi*') ? 'text-brand-700 bg-brand-50 shadow-sm' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/70' }}">
      <i data-lucide="badge-check" class="w-4 h-4 {{ request()->is('admin/Verifikasi*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
      Verifikasi
    </a>

    <a href="{{ url('/admin/KelolaUser') }}" 
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->is('admin/KelolaUser*') ? 'text-brand-700 bg-brand-50 shadow-sm' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/70' }}">
      <i data-lucide="users" class="w-4 h-4 {{ request()->is('admin/KelolaUser*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
      Manajemen User
    </a>

    <p class="px-3 pb-1.5 pt-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Keuangan</p>
    
    <a href="{{ url('/admin/Pemasukan') }}" 
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->is('admin/Pemasukan*') ? 'text-brand-700 bg-brand-50 shadow-sm' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/70' }}">
      <i data-lucide="arrow-down-circle" class="w-4 h-4 {{ request()->is('admin/Pemasukan*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
      Pemasukan
    </a>

    <a href="{{ url('/admin/Pengeluaran') }}" 
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->is('admin/Pengeluaran*') ? 'text-brand-700 bg-brand-50 shadow-sm' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/70' }}">
      <i data-lucide="arrow-up-circle" class="w-4 h-4 {{ request()->is('admin/Pengeluaran*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
      Pengeluaran
    </a>

    <a href="{{ url('/admin/Laporan') }}" 
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->is('admin/Laporan*') ? 'text-brand-700 bg-brand-50 shadow-sm' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/70' }}">
      <i data-lucide="file-bar-chart" class="w-4 h-4 {{ request()->is('admin/Laporan*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
      Laporan
    </a>

    <p class="px-3 pb-1.5 pt-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Sistem</p>
    
    <a href="{{ url('/admin/Profile') }}" 
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->is('admin/Profile*') ? 'text-brand-700 bg-brand-50 shadow-sm' : 'text-slate-600 hover:text-brand-700 hover:bg-brand-50/70' }}">
      <i data-lucide="settings" class="w-4 h-4 {{ request()->is('admin/Profile*') ? 'text-brand-600' : 'text-slate-400' }}"></i>
      Pengaturan
    </a>
  </nav>

  <!-- LOGOUT FOOTER -->
  <div class="p-3 border-t border-slate-100 shrink-0">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-red-600 hover:bg-red-50 transition">
        <i data-lucide="log-out" class="w-4 h-4 text-slate-400"></i>
        Keluar
      </button>
    </form>
  </div>
</aside>