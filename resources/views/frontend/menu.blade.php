@extends('layouts.frontend') 
{{-- Menggunakan layout frontend utama --}}

@section('title', 'Menu') 
{{-- Judul halaman yang akan tampil di tab browser --}}

@section('content')
<!-- ================= HERO SECTION ================= -->
<!-- Bagian header halaman menu -->
<section class="bg-gradient-to-br from-coffee-dark to-coffee-brown text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Judul utama halaman -->
        <h1 class="text-5xl font-bold font-heading mb-4">Our Menu</h1>
        <!-- Deskripsi singkat halaman -->
        <p class="text-xl text-cream/90">
            Discover our delicious selection of coffee and treats
        </p>
    </div>
</section>

<!-- ================= MENU SECTION ================= -->
<!-- Menampilkan daftar kategori dan produk -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Looping data kategori --}}
        @forelse($categories as $category)
        <div class="mb-16">

            <!-- Header kategori -->
            <div class="flex items-center mb-8">
                <!-- Nama kategori -->
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

            <!-- Grid produk dalam kategori -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Looping data produk berdasarkan kategori --}}
                @forelse($category->products as $product)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">

                    <!-- Placeholder gambar produk -->
                    <div class="h-40 bg-gradient-to-br from-light-coffee to-coffee-brown flex items-center justify-center">
                        <span class="text-5xl">☕</span>
                    </div>

                    <!-- Informasi produk -->
                    <div class="p-5">
                        <!-- Nama produk -->
                        <h3 class="text-lg font-bold text-coffee-dark mb-2">
                            {{ $product->name }}
                        </h3>

                        <!-- Deskripsi produk (dibatasi 60 karakter) -->
                        <p class="text-sm text-gray-600 mb-3 h-10 overflow-hidden">
                            {{ Str::limit($product->description, 60) }}
                        </p>

                        <!-- Harga dan status ketersediaan -->
                        <div class="flex items-center justify-between">
                            <!-- Harga produk -->
                            <span class="text-xl font-bold text-gold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>

                            {{-- Status ketersediaan produk --}}
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
                    <p class="text-gray-500">
                        No products in this category
                    </p>
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
