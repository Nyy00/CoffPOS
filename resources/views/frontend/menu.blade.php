{{-- Menggunakan layout utama frontend --}}
@extends('layouts.frontend')

{{-- Judul halaman (digunakan di <title>) --}}
@section('title', 'Menu')

{{-- Konten utama halaman --}}
@section('content')

<!-- ================= HERO SECTION ================= -->
<!-- Bagian header / banner menu -->
<section class="bg-gradient-to-br from-coffee-dark to-coffee-brown text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        {{-- Judul halaman --}}
        <h1 class="text-5xl font-bold font-heading mb-4">Our Menu</h1>

        {{-- Subjudul / deskripsi --}}
        <p class="text-xl text-cream/90">
            Discover our delicious selection of coffee and treats
        </p>
    </div>
</section>

<!-- ================= MENU SECTION ================= -->
<!-- Bagian daftar kategori dan produk -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Looping data kategori --}}
        @forelse($categories as $category)

        <div class="mb-16">

            <!-- Judul kategori -->
            <div class="flex items-center mb-8">
                {{-- Nama kategori --}}
                <h2 class="text-3xl font-bold font-heading text-coffee-dark">
                    {{ $category->name }}
                </h2>

                {{-- Deskripsi kategori (jika ada) --}}
                @if($category->description)
                <p class="ml-4 text-gray-600">
                    - {{ $category->description }}
                </p>
                @endif
            </div>

            <!-- Grid produk -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Looping produk berdasarkan kategori --}}
                @forelse($category->products as $product)

                <!-- Card produk -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">

                    <!-- Gambar produk -->
                    <div class="relative h-40 bg-gradient-to-br from-light-coffee to-coffee-brown flex items-center justify-center overflow-hidden group">

                        {{-- Jika produk memiliki gambar --}}
                        @if($product->image)
                            @php
                                $fallbackImage = 'placeholder-product.png';
                                $productName = strtolower($product->name);
                                if (str_contains($productName, 'cheesecake')) $fallbackImage = 'cheesecake.jpg';
                                elseif (str_contains($productName, 'sandwich')) $fallbackImage = 'sandwich.jpg';
                                elseif (str_contains($productName, 'tiramisu')) $fallbackImage = 'tiramisu.jpg';
                                elseif (str_contains($productName, 'chocolate')) $fallbackImage = 'chocolate.jpg';
                                elseif (str_contains($productName, 'croissant')) $fallbackImage = 'croissants.jpg';
                                elseif (str_contains($productName, 'americano')) $fallbackImage = 'americano.jpg';
                                elseif (str_contains($productName, 'latte')) $fallbackImage = 'latte.jpg';
                                elseif (str_contains($productName, 'cappuccino')) $fallbackImage = 'cappuccino.jpg';
                                elseif (str_contains($productName, 'espresso')) $fallbackImage = 'espresso.jpg';
                                elseif (str_contains($productName, 'mocha')) $fallbackImage = 'mocha.jpg';
                                elseif (str_contains($productName, 'tea')) $fallbackImage = 'green-tea.jpg';
                            @endphp
                            <img src="@productImage($product->image, $product->name)" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-300">>
                        @else
                            {{-- Icon default jika tidak ada gambar --}}
                            <svg class="w-20 h-20 text-cream" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v2.5a.75.75 0 001.5 0v-2.5a.75.75 0 01.75-.75h4.75a.75.75 0 01.75.75v7a.75.75 0 01-.75.75H5.5a.75.75 0 01-.75-.75v-2.5a.75.75 0 00-1.5 0v2.5A2.25 2.25 0 005.5 16.25h4.75a2.25 2.25 0 002.25-2.25v-7a2.25 2.25 0 00-2.25-2.25zm5.25 3a.75.75 0 01.75.75v9a.75.75 0 01-1.5 0V5.25a.75.75 0 01.75-.75z"/>
                            </svg>
                        @endif
                    </div>

                    <!-- Detail produk -->
                    <div class="p-5">

                        {{-- Nama produk --}}
                        <h3 class="text-lg font-bold text-coffee-dark mb-2">
                            {{ $product->name }}
                        </h3>

                        {{-- Deskripsi produk (dibatasi 60 karakter) --}}
                        <p class="text-sm text-gray-600 mb-3 h-10 overflow-hidden">
                            {{ Str::limit($product->description, 60) }}
                        </p>

                        <!-- Harga & status -->
                        <div class="flex items-center justify-between">

                            {{-- Harga produk --}}
                            <span class="text-xl font-bold text-gold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>

                            {{-- Status ketersediaan --}}
                            @if($product->is_available)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">
                                    Available
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">
                                    Sold Out
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Jika kategori tidak memiliki produk --}}
                @empty
                <div class="col-span-4 text-center py-8">
                    <p class="text-gray-500">No products in this category</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Jika tidak ada kategori sama sekali --}}
        @empty
        <div class="text-center py-16">
            <p class="text-gray-500 text-lg">
                No menu available at the moment
            </p>
        </div>
        @endforelse
    </div>
</section>
@endsection
