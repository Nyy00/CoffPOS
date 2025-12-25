<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-coffee-dark">Welcome Back!</h2>
        <p class="text-gray-600 mt-2">Login to access your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-coffee-brown shadow-sm focus:ring-gold" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full bg-coffee-dark text-white py-3 rounded-lg font-semibold hover:bg-coffee-brown transition">
                Log in
            </button>
        </div>

        <div class="mt-4 text-center text-sm">
            <span class="text-gray-600">Don't have an account?</span>
            <a href="{{ route('register') }}" class="text-gold font-semibold hover:text-coffee-brown">
                Register here
            </a>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-coffee-dark flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>
        </div>
    </form>

    <!-- Demo Credentials -->
    <div class="mt-6 p-4 bg-cream rounded-lg">
        <p class="text-xs font-semibold text-coffee-dark mb-2">Demo Credentials:</p>
        <div class="text-xs text-gray-600 space-y-1">
            <p><strong>Admin:</strong> admin@coffpos.com / password</p>
            <p><strong>Manager:</strong> manager@coffpos.com / password</p>
            <p><strong>Cashier:</strong> cashier@coffpos.com / password</p>
        </div>
    </div>
</x-guest-layout>
