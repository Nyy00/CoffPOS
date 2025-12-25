@extends('layouts.frontend')

@section('title', 'Menu')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-coffee-dark to-coffee-brown text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl font-bold font-heading mb-4">Our Menu</h1>
        <p class="text-xl text-cream/90">Discover our delicious selection of coffee and treats</p>
    </div>
</section>

<!-- Menu Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @forelse($categories as $category)
        <div class="mb-16">
            <div class="flex items-center mb-8">
                <h2 class="text-3xl font-bold font-heading text-coffee-dark">{{ $category->name }}</h2>
                @if($category->description)
                <p class="ml-4 text-gray-600">- {{ $category->description }}</p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($category->products as $product)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="relative h-40 bg-gradient-to-br from-light-coffee to-coffee-brown flex items-center justify-center overflow-hidden group">
                        @if($product->image)
                            <img src="{{ asset('images/products/' . str_replace('products/', '', $product->image)) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                                 onerror="this.onerror=null; this.src='{{ Storage::url($product->image) }}';">
                        @else
                            <svg class="w-20 h-20 text-cream" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v2.5a.75.75 0 001.5 0v-2.5a.75.75 0 01.75-.75h4.75a.75.75 0 01.75.75v7a.75.75 0 01-.75.75H5.5a.75.75 0 01-.75-.75v-2.5a.75.75 0 00-1.5 0v2.5A2.25 2.25 0 005.5 16.25h4.75a2.25 2.25 0 002.25-2.25v-7a2.25 2.25 0 00-2.25-2.25zm5.25 3a.75.75 0 01.75.75v9a.75.75 0 01-1.5 0V5.25a.75.75 0 01.75-.75z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-coffee-dark mb-2">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-600 mb-3 h-10 overflow-hidden">{{ Str::limit($product->description, 60) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-gold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            @if($product->is_available)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">Available</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">Sold Out</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-8">
                    <p class="text-gray-500">No products in this category</p>
                </div>
                @endforelse
            </div>
        </div>
        @empty
        <div class="text-center py-16">
            <p class="text-gray-500 text-lg">No menu available at the moment</p>
        </div>
        @endforelse
    </div>
</section>
@endsection
