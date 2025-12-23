@props(['variant' => 'default', 'size' => 'md', 'rounded' => true])

@php
// Class warna badge berdasarkan variant
$variants = [
    'default' => 'bg-gray-100 text-gray-800',
    'primary' => 'bg-blue-100 text-blue-800',
    'secondary' => 'bg-gray-100 text-gray-800',
    'success' => 'bg-green-100 text-green-800',
    'danger' => 'bg-red-100 text-red-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'info' => 'bg-blue-100 text-blue-800',
    'light' => 'bg-gray-50 text-gray-600',
    'dark' => 'bg-gray-800 text-white',
];

// Class ukuran badge
$sizes = [
    'sm' => 'px-2 py-1 text-xs',
    'md' => 'px-2.5 py-0.5 text-sm',
    'lg' => 'px-3 py-1 text-base',
];

// Menentukan bentuk sudut badge
$roundedClass = $rounded ? 'rounded-full' : 'rounded';
@endphp

{{-- Elemen badge --}}
<span
    {{ $attributes->merge([
        'class' => 'inline-flex items-center font-medium '
            . $variants[$variant] . ' '
            . $sizes[$size] . ' '
            . $roundedClass
    ]) }}
>
    {{-- Isi badge --}}
    {{ $slot }}
</span>
