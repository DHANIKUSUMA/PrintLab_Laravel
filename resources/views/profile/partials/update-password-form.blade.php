<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

<form action="{{ route('password.update') }}" method="post">
    @csrf
    @method('put')

    <div class="space-y-4">
        <div class="grid sm:grid-cols-3 gap-4 items-center">
            <label for="current_password" class="text-sm font-medium text-slate-700">Password Saat Ini</label>
            <div class="sm:col-span-2">
                <input type="password" name="current_password" id="current_password" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" required>
                @error('current_password', 'updatePassword') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid sm:grid-cols-3 gap-4 items-center">
            <label for="password" class="text-sm font-medium text-slate-700">Password Baru</label>
            <div class="sm:col-span-2">
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" required>
                @error('password', 'updatePassword') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid sm:grid-cols-3 gap-4 items-center">
            <label for="password_confirmation" class="text-sm font-medium text-slate-700">Konfirmasi Password</label>
            <div class="sm:col-span-2">
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" required>
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button type="submit" class="bg-brand-600 text-white px-5 py-2 rounded-lg hover:bg-brand-700 transition text-sm font-semibold">Ubah Password</button>
    </div>
</form>

</section>
