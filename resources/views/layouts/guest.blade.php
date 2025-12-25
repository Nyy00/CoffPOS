<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CoffPOS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700|inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-cream to-light-coffee">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="{{ route('home') }}" class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-gold to-yellow-400 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-coffee-dark" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v2.5a.75.75 0 001.5 0v-2.5a.75.75 0 01.75-.75h4.75a.75.75 0 01.75.75v7a.75.75 0 01-.75.75H5.5a.75.75 0 01-.75-.75v-2.5a.75.75 0 00-1.5 0v2.5A2.25 2.25 0 005.5 16.25h4.75a2.25 2.25 0 002.25-2.25v-7a2.25 2.25 0 00-2.25-2.25zm5.25 3a.75.75 0 01.75.75v9a.75.75 0 01-1.5 0V5.25a.75.75 0 01.75-.75z"/>
                        </svg>
                    </div>
                    <span class="ml-2 text-3xl font-bold text-coffee-dark">CoffPOS</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
