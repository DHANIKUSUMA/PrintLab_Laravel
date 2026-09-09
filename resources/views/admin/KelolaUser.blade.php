@include('layouts.header', ['title' => 'Kelola User - PrintLab'])

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

      <!-- TOPBAR -->
      <header class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-slate-100">
        <div class="px-4 sm:px-6 h-16 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 rounded-xl text-slate-600 hover:bg-slate-100">
              <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <h1 id="pageTitle" class="text-lg font-bold text-slate-800 tracking-tight">Kelola User</h1>
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

        <!-- TABLE USER -->
        <div class="bg-white rounded-xl border border-slate-100 overflow-hidden shadow-sm">
          <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
            <h3 class="font-bold text-slate-800 text-sm">Daftar User</h3>
            <div class="relative">
              <i data-lucide="search" class="w-4 h-4 text-slate-300 absolute left-3 top-1/2 -translate-y-1/2"></i>
              <input type="text" id="userSearch" placeholder="Cari nama / email..." class="text-sm pl-9 pr-3 py-2 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-100 focus:border-brand-600 w-56">
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="text-left text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100 bg-slate-50/50">
                  <th class="px-5 py-3 font-semibold">Nama</th>
                  <th class="px-5 py-3 font-semibold">Email</th>
                  <th class="px-5 py-3 font-semibold">Role</th>
                  <th class="px-5 py-3 font-semibold">Status</th>
                  <th class="px-5 py-3 font-semibold text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50" id="userTableBody">
                @forelse($users as $user)
                  <tr class="hover:bg-slate-50/60 transition">
                    <td class="px-5 py-3.5">
                      <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $user->email }}</td>
                    <td class="px-5 py-3.5">
                      @if($user->role === 'admin')
                        <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full">Admin</span>
                      @else
                        <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">Pengguna</span>
                      @endif
                    </td>
                    <td class="px-5 py-3.5">
                      @php
                        $status = strtolower($user->status ?? 'aktif');
                        $badgeClass = ($status === 'aktif') ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700';
                      @endphp
                      <span class="{{ $badgeClass }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ ucfirst($status) }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                      <div class="flex items-center justify-center gap-2">
                        @if($user->role !== 'admin')
                          @if($status === 'aktif')
                            <form action="{{ route('admin.kelola-user.update-status') }}" method="POST">
                              @csrf
                              <input type="hidden" name="id_user" value="{{ $user->id_user }}">
                              <input type="hidden" name="status" value="nonaktif">
                              <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" title="Nonaktifkan User">
                                <i data-lucide="user-x" class="w-4 h-4"></i>
                              </button>
                            </form>
                          @else
                            <form action="{{ route('admin.kelola-user.update-status') }}" method="POST">
                              @csrf
                              <input type="hidden" name="id_user" value="{{ $user->id_user }}">
                              <input type="hidden" name="status" value="aktif">
                              <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center transition" title="Aktifkan User">
                                <i data-lucide="user-check" class="w-4 h-4"></i>
                              </button>
                            </form>
                          @endif
                        @else
                          <span class="text-xs text-slate-400 italic">No Action</span>
                        @endif
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada data user.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </main>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();

      // Client-side search
      const searchInput = document.getElementById('userSearch');
      const tableBody = document.getElementById('userTableBody');
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
