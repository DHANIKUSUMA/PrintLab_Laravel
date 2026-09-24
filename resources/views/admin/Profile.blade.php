@include('layouts.header')
<div class="flex min-h-screen">
  <!-- SIDEBAR -->
  @include('layouts.sidebar')
  <div onclick="toggleSidebar()" id="overlay" class="fixed inset-0 bg-black/30 z-30 hidden lg:hidden"></div>
  <div class="flex-1 flex flex-col min-w-0">

    <header class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-slate-100">
      <div class="px-4 sm:px-6 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <button onclick="toggleSidebar()" class="lg:hidden text-slate-500"><i data-lucide="menu" class="w-5 h-5"></i></button>
          <h1 id="pageTitle" class="text-lg font-bold text-slate-800 tracking-tight">Profil Saya</h1>
        </div>
        <div class="flex items-center gap-3">
          <span class="hidden sm:inline text-sm text-slate-500">Halo, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span></span>
        </div>
      </div>
    </header>

    <main class="flex-1 overflow-y-auto p-6 space-y-6">

      <!-- Alert Status -->
      @if (session('status') === 'profile-updated')
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl">
          Profil berhasil diperbarui.
        </div>
      @elseif (session('status') === 'password-updated')
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl">
          Password berhasil diperbarui.
        </div>
      @endif

      <!-- FORM 1: INFORMASI AKUN -->
      <div class="bg-white rounded-xl border border-slate-100 p-5">
        <h3 class="font-bold text-slate-800 text-sm mb-4">Informasi Akun</h3>
        <form action="{{ route('profile.update') }}" method="post">
          @csrf
          @method('patch')
          
          <div class="space-y-4">
            <div class="grid sm:grid-cols-3 gap-4 items-center">
              <label for="name" class="text-sm font-medium text-slate-700">Nama Lengkap</label>
              <div class="sm:col-span-2">
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" required>
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="grid sm:grid-cols-3 gap-4 items-center">
              <label for="email" class="text-sm font-medium text-slate-700">Alamat Email</label>
              <div class="sm:col-span-2">
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" required>
                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
              </div>
            </div>
          </div>

          <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-brand-600 text-white px-5 py-2 rounded-lg hover:bg-brand-700 transition text-sm font-semibold">Simpan Informasi</button>
          </div>
        </form>
      </div>

      <!-- FORM 2: UBAH PASSWORD -->
      <div class="bg-white rounded-xl border border-slate-100 p-5">
        <h3 class="font-bold text-slate-800 text-sm mb-1">Ubah Password</h3>
        <p class="text-xs text-slate-500 mb-4">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>

        <form action="{{ route('password.update') }}" method="post">
          @csrf
          @method('put')

          <div class="space-y-4">
            <div class="grid sm:grid-cols-3 gap-4 items-center">
              <label for="current_password" class="text-sm font-medium text-slate-700">Password Saat Ini</label>
              <div class="sm:col-span-2">
                <input type="password" name="current_password" id="current_password" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" required autocomplete="current-password">
                @error('current_password', 'updatePassword') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="grid sm:grid-cols-3 gap-4 items-center">
              <label for="password" class="text-sm font-medium text-slate-700">Password Baru</label>
              <div class="sm:col-span-2">
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" required autocomplete="new-password">
                @error('password', 'updatePassword') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
              </div>
            </div>
            <div class="grid sm:grid-cols-3 gap-4 items-center">
              <label for="password_confirmation" class="text-sm font-medium text-slate-700">Konfirmasi Password</label>
              <div class="sm:col-span-2">
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" required autocomplete="new-password">
              </div>
            </div>
          </div>

          <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-brand-600 text-white px-5 py-2 rounded-lg hover:bg-brand-700 transition text-sm font-semibold">Perbarui Password</button>
          </div>
        </form>
      </div>

    </main>
  </div>
</div>

<script>
  lucide.createIcons();
</script>
