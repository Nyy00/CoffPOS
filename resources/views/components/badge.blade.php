@props(['variant' => 'default', 'size' => 'md', 'rounded' => true])

@php
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
    // Fun colors untuk expense categories
    'purple' => 'bg-purple-100 text-purple-800',
    'orange' => 'bg-orange-100 text-orange-800',
    'emerald' => 'bg-emerald-100 text-emerald-800',
    'cyan' => 'bg-cyan-100 text-cyan-800',
    'pink' => 'bg-pink-100 text-pink-800',
    'amber' => 'bg-amber-100 text-amber-800',
    'indigo' => 'bg-indigo-100 text-indigo-800',
    'rose' => 'bg-rose-100 text-rose-800',
];

$sizes = [
    'sm' => 'px-2 py-1 text-xs',
    'md' => 'px-2.5 py-0.5 text-sm',
    'lg' => 'px-3 py-1 text-base',
];

$roundedClass = $rounded ? 'rounded-full' : 'rounded';
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center font-medium ' . $variants[$variant] . ' ' . $sizes[$size] . ' ' . $roundedClass]) }}>
    {{ $slot }}
</span>