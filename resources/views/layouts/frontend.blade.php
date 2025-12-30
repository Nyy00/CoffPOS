<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Session messages for JavaScript --}}
    @if(session('success'))
        <meta name="session-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="session-error" content="{{ session('error') }}">
    @endif
    @if(session('warning'))
        <meta name="session-warning" content="{{ session('warning') }}">
    @endif
    @if(session('info'))
        <meta name="session-info" content="{{ session('info') }}">
    @endif

    <title>{{ config('app.name', 'CoffPOS') }} - @yield('title', 'Coffee Shop')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700|inter:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-cream" x-data="{ mobileMenuOpen: false }">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-coffee-dark to-coffee-brown shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center hover:opacity-80 transition">
                        <x-application-logo class="h-10 w-auto" />
                        <span class="ml-3 text-2xl font-bold text-gold">CoffPOS</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="text-cream hover:text-gold px-4 py-2 rounded-lg transition {{ request()->routeIs('home') ? 'text-gold font-semibold bg-coffee-brown' : '' }}">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4-4m-4 4l-4-4m4 4l4 4"/>
                        </svg>
                        Beranda
                    </a>
                    <a href="{{ route('menu') }}" class="text-cream hover:text-gold px-4 py-2 rounded-lg transition {{ request()->routeIs('menu') ? 'text-gold font-semibold bg-coffee-brown' : '' }}">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        Menu
                    </a>
                    <a href="{{ route('about') }}" class="text-cream hover:text-gold px-4 py-2 rounded-lg transition {{ request()->routeIs('about') ? 'text-gold font-semibold bg-coffee-brown' : '' }}">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tentang
                    </a>
                    <a href="{{ route('contact') }}" class="text-cream hover:text-gold px-4 py-2 rounded-lg transition {{ request()->routeIs('contact') ? 'text-gold font-semibold bg-coffee-brown' : '' }}">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kontak
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-gradient-to-r from-gold to-yellow-400 text-coffee-dark px-6 py-2 rounded-full font-semibold hover:shadow-lg transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-cream hover:text-gold px-4 py-2 rounded-lg transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-gold to-yellow-400 text-coffee-dark px-6 py-2 rounded-full font-semibold hover:shadow-lg transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Register
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-cream hover:text-gold transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-coffee-brown border-t border-gold/20 shadow-lg">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-cream hover:text-gold hover:bg-coffee-dark rounded transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4-4m-4 4l-4-4m4 4l4 4"/>
                    </svg>
                    Beranda
                </a>
                <a href="{{ route('menu') }}" class="block px-3 py-2 text-cream hover:text-gold hover:bg-coffee-dark rounded transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Menu
                </a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-cream hover:text-gold hover:bg-coffee-dark rounded transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tentang
                </a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 text-cream hover:text-gold hover:bg-coffee-dark rounded transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Kontak
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gold font-semibold hover:bg-coffee-dark rounded transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-cream hover:text-gold hover:bg-coffee-dark rounded transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-gold font-semibold hover:bg-coffee-dark rounded transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-coffee-dark to-coffee-brown text-cream mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <x-application-logo class="h-10 w-auto" />
                        <span class="text-2xl font-bold text-gold">CoffPOS</span>
                    </div>
                    <p class="text-sm text-cream/80 leading-relaxed">
                        Your favorite coffee shop with the best quality beans and passionate baristas serving perfect cups every day.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold text-gold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        Tautan Cepat
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-gold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9"/>
                            </svg>
                            Beranda
                        </a></li>
                        <li><a href="{{ route('menu') }}" class="hover:text-gold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            Menu
                        </a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-gold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Tentang
                        </a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-gold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Kontak
                        </a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact
                    </h3>
                    <ul class="space-y-3 text-sm text-cream/80">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gold flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span>Universitas Pasundan<br/>Kota Bandung, Jawa Barat 40153</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            +62 812-3456-7890
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            info@coffpos.com
                        </li>
                    </ul>
                </div>

                <!-- Opening Hours -->
                <div>
                    <h3 class="text-lg font-semibold text-gold mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Hours
                    </h3>
                    <ul class="space-y-2 text-sm text-cream/80">
                        <li class="flex justify-between">
                            <span>Mon - Fri:</span>
                            <span class="text-gold font-semibold">7AM - 10PM</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Sat - Sun:</span>
                            <span class="text-gold font-semibold">8AM - 11PM</span>
                        </li>
                        <li class="mt-4 pt-4 border-t border-gold/20">
                            <p class="text-xs italic">Closed on public holidays</p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gold/20 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-cream/60">&copy; {{ date('Y') }} CoffPOS. All rights reserved.</p>
                    <div class="flex gap-4">
                        <a href="#" class="text-cream/60 hover:text-gold transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20v-7.21h-2.42V9.25h2.42V7.07c0-2.49 1.52-3.89 3.75-3.89 1.07 0 1.99.08 2.25.11v2.60h-1.54c-1.22 0-1.46.58-1.46 1.42v1.86h2.92l-.38 3.54h-2.54V20"/>
                            </svg>
                        </a>
                        <a href="#" class="text-cream/60 hover:text-gold transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com/your_instagram" target="_blank" rel="noopener noreferrer" class="text-cream/60 hover:text-gold transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 2.2c3.2 0 3.584.012 4.85.07 1.17.055 1.97.24 2.43.4.59.22 1.01.49 1.45.93.44.44.71.86.93 1.45.16.46.345 1.26.4 2.43.058 1.266.07 1.65.07 4.85s-.012 3.584-.07 4.85c-.055 1.17-.24 1.97-.4 2.43-.22.59-.49 1.01-.93 1.45-.44.44-.86.71-1.45.93-.46.16-1.26.345-2.43.4-1.266.058-1.65.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.055-1.97-.24-2.43-.4-.59-.22-1.01-.49-1.45-.93-.44-.44-.71-.86-.93-1.45-.16-.46-.345-1.26-.4-2.43C2.212 15.584 2.2 15.2 2.2 12s.012-3.584.07-4.85c.055-1.17.24-1.97.4-2.43.22-.59.49-1.01.93-1.45.44-.44.86-.71 1.45-.93.46-.16 1.26-.345 2.43-.4C8.416 2.212 8.8 2.2 12 2.2m0-2.2C8.7 0 8.26.012 7 .07 5.74.129 4.9.3 4.2.54 3.4.81 2.69 1.21 2.05 1.85 1.41 2.49 1.01 3.2.74 4c.24.7.41 1.54.47 2.8C1.988 8.26 1.976 8.7 1.976 12c0 3.3.012 3.74.07 5 .06 1.26.23 2.1.47 2.8.27.8.67 1.51 1.31 2.15.64.64 1.35 1.04 2.15 1.31.7.24 1.54.41 2.8.47 1.26.058 1.7.07 5 .07s3.74-.012 5-.07c1.26-.06 2.1-.23 2.8-.47.8-.27 1.51-.67 2.15-1.31.64-.64 1.04-1.35 1.31-2.15.24-.7.41-1.54.47-2.8.058-1.26.07-1.7.07-5s-.012-3.74-.07-5c-.06-1.26-.23-2.1-.47-2.8-.27-.8-.67-1.51-1.31-2.15C21.49.74 20.78.34 19.98.07c-.7-.24-1.54-.41-2.8-.47C15.74.012 15.3 0 12 0z"/>
                                <path d="M12 5.838A6.162 6.162 0 1 0 18.162 12 6.17 6.17 0 0 0 12 5.838zm0 10.162A4 4 0 1 1 16 12a4 4 0 0 1-4 4z"/>
                                <circle cx="18.406" cy="5.594" r="1.44"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Global Alert System --}}
    <x-global-alert-system />

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('navigation', () => ({
                mobileMenuOpen: false
            }))
        })
    </script>
</body>
</html>
