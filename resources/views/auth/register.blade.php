@include('layouts.header')

<body class="font-sans bg-brand-50 min-h-screen text-slate-800 antialiased">

  <div class="relative min-h-screen flex items-center justify-center overflow-hidden px-4 py-10">

    <!-- Decorative Blobs -->
    <div class="pointer-events-none absolute -top-32 -right-32 w-[420px] h-[420px] sm:w-[480px] sm:h-[480px] rounded-full bg-[radial-gradient(circle,#c7d9ff_0%,transparent_70%)]"></div>
    <div class="pointer-events-none absolute -bottom-28 -left-28 w-[300px] h-[300px] sm:w-[360px] sm:h-[360px] rounded-full bg-[radial-gradient(circle,#dde8ff_0%,transparent_70%)]"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-[0_8px_40px_rgba(30,60,160,0.10)] p-8 sm:p-10 border border-slate-100">

      <!-- Logo & Brand Header -->
      <div class="flex items-center gap-2.5 mb-8">
        <div class="w-10 h-10 rounded-[10px] bg-brand-600 flex items-center justify-center shrink-0 shadow-md shadow-brand-600/30">
          <svg viewBox="0 0 24 24" class="w-[22px] h-[22px] fill-white">
            <path d="M20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6C22 4.9 21.1 4 20 4ZM20 18H4V6H20V18ZM6 10H8V14H6V10ZM9.5 8H11.5V16H9.5V8ZM13 11H15V14H13V11Z"/>
          </svg>
        </div>
        <span class="text-xl font-bold text-slate-800 tracking-tight">PrintLab</span>
      </div>

      <h1 class="text-2xl sm:text-[1.6rem] font-bold text-slate-900 tracking-tight mb-1">Buat akun baru</h1>
      <p class="text-sm text-slate-500 mb-6">Isi data di bawah untuk mulai menggunakan layanan</p>

      <!-- Form Register Laravel -->
      <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Nama Lengkap -->
        <div>
          <label for="name" class="block text-xs font-semibold uppercase tracking-wide text-slate-700 mb-1.5">
            Nama Lengkap
          </label>
          <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            required
            autofocus
            autocomplete="name"
            placeholder="Masukkan nama lengkap"
            class="w-full border-[1.5px] @error('name') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-900 bg-slate-50 placeholder:text-slate-400 outline-none transition focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-brand-600/10"
          >
          @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-xs font-semibold uppercase tracking-wide text-slate-700 mb-1.5">
            Email
          </label>
          <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            required
            autocomplete="username"
            placeholder="Masukkan email"
            class="w-full border-[1.5px] @error('email') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-900 bg-slate-50 placeholder:text-slate-400 outline-none transition focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-brand-600/10"
          >
          @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-xs font-semibold uppercase tracking-wide text-slate-700 mb-1.5">
            Password
          </label>
          <input
            type="password"
            id="password"
            name="password"
            required
            autocomplete="new-password"
            placeholder="Minimal 8 karakter"
            class="w-full border-[1.5px] @error('password') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-900 bg-slate-50 placeholder:text-slate-400 outline-none transition focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-brand-600/10"
          >
          @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Konfirmasi Password -->
        <div>
          <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wide text-slate-700 mb-1.5">
            Konfirmasi Password
          </label>
          <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            required
            autocomplete="new-password"
            placeholder="Ulangi kata sandi"
            class="w-full border-[1.5px] @error('password_confirmation') border-red-500 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm text-slate-900 bg-slate-50 placeholder:text-slate-400 outline-none transition focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-brand-600/10"
          >
          @error('password_confirmation')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Tombol Submit -->
        <button
          type="submit"
          class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-xl py-3.5 mt-2 shadow-lg shadow-brand-600/25 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-600/30 active:translate-y-0"
        >
          Daftar Sekarang
        </button>

      </form>

      <!-- Link Login -->
      <p class="text-center text-sm text-slate-500 mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-brand-600 font-bold hover:underline">Login</a>
      </p>

      <!-- Footer -->
      <div class="mt-7 pt-5 border-t border-slate-100 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} Admin Laboratorium Business Intelligence System
      </div>
    </div>
  </div>
</body>
</html>