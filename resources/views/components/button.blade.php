@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'href' => null
])

@php
// Class tampilan berdasarkan variant button
$variants = [
    'primary' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 text-white',
    'success' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white',
    'danger' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white',
    'warning' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 text-white',
    'info' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white',
    'light' => 'bg-gray-100 hover:bg-gray-200 focus:ring-gray-500 text-gray-900 border border-gray-300',
    'outline' => 'border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white focus:ring-blue-500',
    'outline-primary' => 'border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white focus:ring-blue-500',
    'outline-secondary' => 'border border-gray-600 text-gray-600 hover:bg-gray-600 hover:text-white focus:ring-gray-500',
    'outline-danger' => 'border border-red-600 text-red-600 hover:bg-red-600 hover:text-white focus:ring-red-500',
    'ghost' => 'text-gray-600 hover:bg-gray-100 focus:ring-gray-500',
    'link' => 'text-blue-600 hover:text-blue-800 underline focus:ring-blue-500',
];

// Class ukuran button
$sizes = [
    'xs' => 'px-2.5 py-1.5 text-xs',
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-4 py-2 text-base',
    'xl' => 'px-6 py-3 text-base',
];

// Class dasar button
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200';

// Class tambahan jika disabled / loading
$disabledClasses = 'opacity-50 cursor-not-allowed';

// Gabungan semua class
$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];

// Tambahkan class disabled jika kondisi terpenuhi
if ($disabled || $loading) {
    $classes .= ' ' . $disabledClasses;
}
@endphp

{{-- Jika href diisi, render sebagai link --}}
@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>

        {{-- Spinner loading --}}
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291
                         A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938
                         l3-2.647z"></path>
            </svg>
        @endif

        {{-- Teks / konten button --}}
        {{ $slot }}
    </a>

{{-- Jika tidak ada href, render sebagai button --}}
@else
    <button
        type="{{ $type }}"
        {{-- Disable button jika disabled atau loading --}}
        {{ $disabled || $loading ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => $classes]) }}
    >

        {{-- Spinner loading --}}
        @if($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291
                         A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938
                         l3-2.647z"></path>
            </svg>
        @endif

        {{-- Teks / konten button --}}
        {{ $slot }}
    </button>
@endif
