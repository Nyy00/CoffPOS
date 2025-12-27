<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Mengarahkan user ke halaman login Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani data callback dari Google setelah user login.
     */
    public function handleGoogleCallback()
    {
        try {
            // 1. Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Debug: Log Google user data
            \Log::info('Google OAuth Callback', [
                'google_id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            // 2. Cari user di database berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // 3. Jika user belum ada, buat user baru
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'role' => 'customer', // Role default untuk user yang login via Google
                    'password' => Hash::make(Str::random(16)), // Password acak
                    'avatar' => $googleUser->getAvatar(), // Simpan foto profil dari Google
                    'phone' => null, // Phone dibiarkan kosong dulu
                ]);
                
                \Log::info('New Google user created', ['user_id' => $user->id, 'email' => $user->email]);
            } else {
                // 4. Jika user sudah ada (email sama), update google_id dan avatar-nya
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(), // Update avatar jika user ganti foto di Google
                ]);
                
                \Log::info('Existing user updated', ['user_id' => $user->id, 'email' => $user->email]);
            }

            // 5. Login-kan user ke sistem
            Auth::login($user);
            
            // Debug: Verify user is logged in
            \Log::info('User logged in', [
                'user_id' => Auth::id(),
                'is_authenticated' => Auth::check(),
                'user_role' => $user->role
            ]);

            // 6. Redirect user sesuai Role (Logic CoffPOS)
            if ($user->role === 'admin') {
                \Log::info('Redirecting admin to dashboard');
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'manager') {
                \Log::info('Redirecting manager to dashboard');
                return redirect()->intended('/admin/dashboard-manager');
            } else {
                \Log::info('Redirecting customer to homepage');
                return redirect()->intended('/'); // Halaman Frontend / Menu
            }

        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Google OAuth Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Jika terjadi error atau user membatalkan login
            return redirect()->route('login')->with('error', 'Login Google gagal atau dibatalkan: ' . $e->getMessage());
        }
    }
}