<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> {{-- Bahasa sesuai locale aplikasi --}}
    <head>
        <meta charset="utf-8"> {{-- Encoding UTF-8 --}}
        <meta name="viewport" content="width=device-width, initial-scale=1"> {{-- Responsive viewport --}}
        <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Token keamanan CSRF --}}

        <title>{{ config('app.name', 'CoffPOS') }}</title> {{-- Judul aplikasi --}}

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net"> {{-- Optimasi koneksi font --}}
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700|inter:400,500,600&display=swap" rel="stylesheet" /> {{-- Font Poppins & Inter --}}

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Load asset via Vite --}}
    </head>

    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-cream to-light-coffee">
        {{-- Background gradient & styling dasar --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            {{-- Logo & nama aplikasi --}}
            <div>
                <a href="{{ route('home') }}" class="flex items-center">
                    <x-application-logo class="h-12 w-auto" />
                    <span class="ml-2 text-3xl font-bold text-coffee-dark">CoffPOS</span>
                </a>
            </div>

            {{-- Card konten (login / register / form) --}}
            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-2xl">
                {{ $slot }} {{-- Slot konten dari child view --}}
            </div>

        </div>
    </body>
</html>
