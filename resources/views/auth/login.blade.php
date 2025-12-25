<x-guest-layout>
    {{-- Layout khusus untuk user yang belum login (guest) --}}

    <!-- ================= HEADER LOGIN ================= -->
    <div class="mb-6 text-center">
        <!-- Judul halaman login -->
        <h2 class="text-3xl font-bold text-coffee-dark">Welcome Back!</h2>
        <!-- Deskripsi singkat -->
        <p class="text-gray-600 mt-2">Login to access your account</p>
    </div>

    <!-- ================= SESSION STATUS ================= -->
    <!-- Menampilkan pesan status (contoh: logout berhasil, reset password berhasil) -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- ================= FORM LOGIN ================= -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        {{-- Token keamanan untuk mencegah CSRF --}}

        <!-- ================= EMAIL ================= -->
        <div>
            <!-- Label input email -->
            <x-input-label for="email" :value="__('Email')" />

            <!-- Input email -->
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />

            <!-- Pesan error validasi email -->
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- ================= PASSWORD ================= -->
        <div class="mt-4">
            <!-- Label input password -->
            <x-input-label for="password" :value="__('Password')" />

            <!-- Input password -->
            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />

            <!-- Pesan error validasi password -->
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- ================= REMEMBER ME ================= -->
        <div class="block mt-4">
            <!-- Checkbox remember me -->
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-gray-300 text-coffee-brown shadow-sm focus:ring-gold"
                    name="remember"
                >
                <span class="ms-2 text-sm text-gray-600">
                    {{ __('Remember me') }}
                </span>
            </label>
        </div>

        <!-- ================= BUTTON LOGIN ================= -->
        <div class="mt-6">
            <!-- Tombol submit login -->
            <button
                type="submit"
                class="w-full bg-coffee-dark text-white py-3 rounded-lg font-semibold hover:bg-coffee-brown transition"
            >
                Log in
            </button>
        </div>

        <!-- ================= LINK REGISTER ================= -->
        <div class="mt-4 text-center text-sm">
            <span class="text-gray-600">Don't have an account?</span>
            <a
                href="{{ route('register') }}"
                class="text-gold font-semibold hover:text-coffee-brown"
            >
                Register here
            </a>
        </div>

        <!-- ================= LINK BACK HOME ================= -->
        <div class="mt-4 text-center">
            <a
                href="{{ route('home') }}"
                class="text-sm text-gray-600 hover:text-coffee-dark"
            >
                ← Back to Home
            </a>
        </div>
    </form>

    <!-- ================= DEMO CREDENTIALS ================= -->
    <!-- Informasi akun demo untuk testing aplikasi -->
    <div class="mt-6 p-4 bg-cream rounded-lg">
        <p class="text-xs font-semibold text-coffee-dark mb-2">
            Demo Credentials:
        </p>

        <div class="text-xs text-gray-600 space-y-1">
            <p><strong>Admin:</strong> admin@coffpos.com / password</p>
            <p><strong>Manager:</strong> manager@coffpos.com / password</p>
            <p><strong>Cashier:</strong> cashier@coffpos.com / password</p>
        </div>
    </div>
</x-guest-layout>
