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
        // Log bahwa callback dimulai
        \Log::info('Google OAuth Callback Started', [
            'request_url' => request()->fullUrl(),
            'request_method' => request()->method(),
            'user_agent' => request()->userAgent(),
        ]);

        try {
            // 1. Ambil data user dari Google
            \Log::info('Attempting to get Google user data');
            $googleUser = Socialite::driver('google')->user();

            // Debug: Log Google user data
            \Log::info('Google OAuth Callback', [
                'google_id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            // 2. Cari user di database berdasarkan email
            \Log::info('Searching for existing user', ['email' => $googleUser->getEmail()]);
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // 3. Jika user belum ada, buat user baru
                \Log::info('Creating new user');
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
                \Log::info('Updating existing user', ['user_id' => $user->id]);
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(), // Update avatar jika user ganti foto di Google
                ]);
                
                \Log::info('Existing user updated', ['user_id' => $user->id, 'email' => $user->email]);
            }

            // 5. Login-kan user ke sistem
            \Log::info('Attempting to login user', ['user_id' => $user->id]);
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

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Google OAuth Invalid State Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->route('login')->with('error', 'Google login session expired. Please try again.');
            
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            \Log::error('Google OAuth HTTP Client Exception', [
                'message' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'No response',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
            
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Google OAuth General Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Jika terjadi error atau user membatalkan login
            return redirect()->route('login')->with('error', 'Login Google gagal: ' . $e->getMessage());
        }
    }
}