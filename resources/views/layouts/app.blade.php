<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> {{-- Set bahasa sesuai locale aplikasi --}}
    <head>
        <meta charset="utf-8"> {{-- Encoding karakter UTF-8 --}}
        <meta name="viewport" content="width=device-width, initial-scale=1"> {{-- Responsive viewport --}}
        <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Token keamanan CSRF --}}

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

        <title>{{ config('app.name', 'Laravel') }}</title> {{-- Judul aplikasi --}}

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net"> {{-- Optimasi koneksi font --}}
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> {{-- Font Figtree --}}

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Load CSS & JS via Vite --}}
    </head>
    <body class="font-sans antialiased"> {{-- Font default & anti-alias --}}
        <div class="min-h-screen bg-gray-100"> {{-- Wrapper utama halaman --}}

            @include('layouts.navigation') {{-- Include navbar / navigasi --}}

            <!-- Page Heading -->
            @isset($header) {{-- Cek apakah halaman memiliki header --}}
                <header class="bg-white shadow"> {{-- Container header --}}
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"> {{-- Layout responsif --}}
                        {{ $header }} {{-- Konten header dari view --}}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main> {{-- Konten utama halaman --}}
                @yield('content') {{-- Slot konten dari child view --}}
            </main>

        </div>

        {{-- Global Alert System --}}
        <x-global-alert-system />
    </body>
</html>
