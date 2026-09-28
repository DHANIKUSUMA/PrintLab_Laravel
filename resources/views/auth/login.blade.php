@include('layouts.header')

</head>
<body class="font-sans bg-brand-50 min-h-screen antialiased text-slate-800">
  <div class="relative min-h-screen flex items-center justify-center overflow-hidden px-4 py-10">

    <!-- Decorative background blobs -->
    <div class="pointer-events-none absolute -top-32 -right-32 w-[420px] h-[420px] sm:w-[480px] sm:h-[480px] rounded-full bg-[radial-gradient(circle,#c7d9ff_0%,transparent_70%)]"></div>
    <div class="pointer-events-none absolute -bottom-28 -left-28 w-[300px] h-[300px] sm:w-[360px] sm:h-[360px] rounded-full bg-[radial-gradient(circle,#dde8ff_0%,transparent_70%)]"></div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-3xl shadow-[0_8px_40px_rgba(30,60,160,0.08)] border border-slate-100 px-8 sm:px-10 pt-10 pb-9">

      <!-- Logo & Header -->
      <div class="flex items-center gap-3 mb-6">
        <x-application-logo class="w-10 h-10 object-contain shrink-0" />
        <span class="text-xl font-bold text-slate-900 tracking-tight">PrintLab</span>
      </div>

      <h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-1.5">Selamat datang kembali</h1>
      <p class="text-sm text-slate-500 mb-6">Masuk ke akun Anda untuk melanjutkan</p>

      <!-- Alert Pesan Sukses / Error -->
      @if (session('status'))
        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium">
          {{ session('status') }}
        </div>
      @endif

      @if (session('error'))
        <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium">
          {{ session('error') }}
        </div>
      @endif

      <!-- Form Login -->
      <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Input Email -->
        <div>
          <label for="email" class="block text-xs font-semibold uppercase tracking-wide text-slate-700 mb-1.5">
            Email
          </label>
          <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="nama@email.com"
            required
            autofocus
            autocomplete="username"
            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 bg-slate-50 placeholder:text-slate-400 outline-none transition focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-brand-600/10"
          >
          @error('email')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
          @enderror
        </div>

        <!-- Input Password -->
        <div x-data="{ showPassword: false }">
          <div class="flex items-center justify-between mb-1.5">
            <label for="password" class="block text-xs font-semibold uppercase tracking-wide text-slate-700">
              Password
            </label>
            @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium hover:underline">
                Lupa Password?
              </a>
            @endif
          </div>
          <div class="relative">
            <input
              :type="showPassword ? 'text' : 'password'"
              type="password"
              id="password"
              name="password"
              placeholder="••••••••"
              required
              autocomplete="current-password"
              class="w-full border border-slate-200 rounded-xl pl-4 pr-11 py-3 text-sm text-slate-900 bg-slate-50 placeholder:text-slate-400 outline-none transition focus:border-brand-600 focus:bg-white focus:ring-4 focus:ring-brand-600/10"
            >
            <button
              type="button"
              @click="showPassword = !showPassword"
              class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition p-1 cursor-pointer"
              aria-label="Tampilkan / Sembunyikan Password"
            >
              <!-- Eye Open (Lihat Password) -->
              <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <!-- Eye Closed / Slashed (Sembunyikan Password) -->
              <svg x-show="showPassword" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
              </svg>
            </button>
          </div>
          @error('password')
            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
          @enderror
        </div>

        <!-- Tombol Submit -->
        <button
          type="submit"
          class="w-full bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white font-semibold text-sm rounded-xl py-3.5 shadow-md shadow-brand-600/20 transition duration-150 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-brand-600/25 active:translate-y-0"
        >
          Masuk ke Akun
        </button>
      </form>

      <!-- Divider -->
      <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-slate-200"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
          <span class="bg-white px-3 text-slate-400 font-medium tracking-wider">Atau masuk dengan</span>
        </div>
      </div>

      <!-- Tombol Login Google -->
      <a
        href="{{ route('auth.google') }}"
        class="w-full flex items-center justify-center gap-3 border border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50 active:bg-slate-100 text-slate-700 font-medium text-sm rounded-xl py-3 shadow-sm transition duration-150 hover:-translate-y-0.5 hover:shadow active:translate-y-0"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 48 48">
          <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
          <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
          <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
          <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
        </svg>
        <span>Masuk dengan Google</span>
      </a>

      <!-- Link Daftar Akun -->
      <div class="text-center text-sm text-slate-500 mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-brand-600 font-semibold hover:text-brand-700 hover:underline">
          Daftar di sini
        </a>
      </div>

      <!-- Footer -->
      <div class="mt-8 pt-5 border-t border-slate-100 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} PrintLab — Solusi Percetakan Digital
      </div>

    </div>
  </div>
</body>
</html>
