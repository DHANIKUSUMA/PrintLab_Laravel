<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        $redirectUrl = config('services.google.redirect') ?: url('/auth/google/callback');
        return Socialite::driver('google')->redirectUrl($redirectUrl)->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $redirectUrl = config('services.google.redirect') ?: url('/auth/google/callback');
            $googleUser = Socialite::driver('google')->redirectUrl($redirectUrl)->user();
            
            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Update google_id jika user sudah ada sebelumnya via email
                $user->update([
                    'google_id' => $googleUser->id,
                ]);
            } else {
                // Buat user baru jika belum terdaftar
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role' => 'pengguna',
                    'status' => 'aktif',
                    'password' => null,
                ]);
            }

            Auth::login($user, remember: true);

            // Redirect sesuai role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }

            return redirect()->intended(route('dashboard', absolute: false));

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal masuk menggunakan Google.');
        }
    }
}
