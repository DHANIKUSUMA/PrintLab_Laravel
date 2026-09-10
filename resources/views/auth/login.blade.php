@include('layouts.header')

</head><body class="font-sans bg-brand-50 min-h-screen">
  <div class="relative min-h-screen flex items-center justify-center overflow-hidden px-4 py-8">

    <!-- decorative blobs -->
    <div class="pointer-events-none absolute -top-32 -right-32 w-[420px] h-[420px] sm:w-[480px] sm:h-[480px] rounded-full bg-[radial-gradient(circle,#c7d9ff_0%,transparent_70%)]"></div>
    <div class="pointer-events-none absolute -bottom-28 -left-28 w-[300px] h-[300px] sm:w-[360px] sm:h-[360px] rounded-full bg-[radial-gradient(circle,#dde8ff_0%,transparent_70%)]"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-[0_8px_40px_rgba(30,60,160,0.10)] px-8 sm:px-10 pt-11 pb-9">

      <!-- logo -->
      <div class="flex items-center gap-2.5 mb-8">
        <x-application-logo class="w-10 h-10 object-contain shrink-0" />
        <span class="text-xl font-bold text-slate-800 tracking-tight">PrintLab</span>
      </div>

      <h1 class="text-2xl sm:text-[1.6rem] font-bold text-slate-900 tracking-tight mb-1.5">Selamat datang kembali</h1>
      <p class="text-sm text-slate-500 mb-8">Masuk ke akun Anda untuk melanjutkan</p>

      <form action="{{ route('login') }}" method="POST">

        <div class="mb-5">
          <label for="email" class="block text-xs font-semibold uppercase tracking-wide text-slate-700 mb-1.5">
            Email
          </label>
          <input
            type="text"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Masukkan email"
            required
            autocomplete="username"
            class="w-full border-[1.5px] border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 bg-slate-50 placeholder:text-slate-400 outline-none transition focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-brand-600/10"
          >
          @error('email')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div class="mb-5">
          <label for="password" class="block text-xs font-semibold uppercase tracking-wide text-slate-700 mb-1.5">
            Password
          </label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Masukkan password"
            required
            autocomplete="new-password"
            class="w-full border-[1.5px] border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 bg-slate-50 placeholder:text-slate-400 outline-none transition focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-brand-600/10"
          >
          @error('password')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Tombol Submit -->
        <button
          type="submit"
          class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-xl py-3.5 mt-2 shadow-lg shadow-brand-600/25 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-600/30 active:translate-y-0"
        >
          Masuk ke Akun
        </button>

      </form>

      <div class="text-center text-sm text-slate-500 mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-brand-600 font-bold hover:underline">Daftar di sini</a>
      </div>

      <div class="mt-7 pt-5 border-t border-slate-100 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} MediaHub — Portal Pengajuan Media
      </div>

    </div>
  </div>

</body>
