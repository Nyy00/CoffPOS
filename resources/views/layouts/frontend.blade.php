<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CoffPOS') }} - @yield('title', 'Coffee Shop')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700|inter:400,500,600" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-cream">
    <!-- Navigation -->
    <nav class="bg-coffee-dark shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center">
                        <span class="text-3xl">☕</span>
                        <span class="ml-2 text-2xl font-bold text-gold">CoffPOS</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-cream hover:text-gold transition {{ request()->routeIs('home') ? 'text-gold font-semibold' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('menu') }}" class="text-cream hover:text-gold transition {{ request()->routeIs('menu') ? 'text-gold font-semibold' : '' }}">
                        Menu
                    </a>
                    <a href="{{ route('about') }}" class="text-cream hover:text-gold transition {{ request()->routeIs('about') ? 'text-gold font-semibold' : '' }}">
                        About
                    </a>
                    <a href="{{ route('contact') }}" class="text-cream hover:text-gold transition {{ request()->routeIs('contact') ? 'text-gold font-semibold' : '' }}">
                        Contact
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-gold text-coffee-dark px-6 py-2 rounded-full font-semibold hover:bg-light-coffee transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-cream hover:text-gold transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-gold text-coffee-dark px-6 py-2 rounded-full font-semibold hover:bg-light-coffee transition">
                            Register
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-cream hover:text-gold">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-coffee-dark border-t border-gold/20">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-cream hover:text-gold">Home</a>
                <a href="{{ route('menu') }}" class="block px-3 py-2 text-cream hover:text-gold">Menu</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-cream hover:text-gold">About</a>
                <a href="{{ route('contact') }}" class="block px-3 py-2 text-cream hover:text-gold">Contact</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gold font-semibold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-cream hover:text-gold">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-gold font-semibold">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-coffee-dark text-cream mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-xl font-bold text-gold mb-4">CoffPOS</h3>
                    <p class="text-sm text-cream/80">
                        Your favorite coffee shop with the best quality beans and friendly service.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold text-gold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-gold transition">Home</a></li>
                        <li><a href="{{ route('menu') }}" class="hover:text-gold transition">Menu</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-gold transition">About</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-gold transition">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gold mb-4">Contact</h3>
                    <ul class="space-y-2 text-sm text-cream/80">
                        <li>📍 Jl. Merdeka No. 123, Bandung</li>
                        <li>📞 +62 812-3456-7890</li>
                        <li>✉️ info@coffpos.com</li>
                    </ul>
                </div>

                <!-- Opening Hours -->
                <div>
                    <h3 class="text-lg font-semibold text-gold mb-4">Opening Hours</h3>
                    <ul class="space-y-2 text-sm text-cream/80">
                        <li>Monday - Friday: 7AM - 10PM</li>
                        <li>Saturday - Sunday: 8AM - 11PM</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gold/20 mt-8 pt-8 text-center text-sm text-cream/60">
                <p>&copy; {{ date('Y') }} CoffPOS. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('navigation', () => ({
                mobileMenuOpen: false
            }))
        })
    </script>
</body>
</html>
