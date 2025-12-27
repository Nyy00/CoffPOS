@extends('layouts.app') 
{{-- Menggunakan layout utama aplikasi --}}

@section('title', 'Product Details - ' . $product->name) 
{{-- Mengatur judul halaman sesuai nama produk --}}

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ================= HEADER & BREADCRUMB ================= --}}
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    
                    {{-- Tombol kembali ke halaman daftar produk --}}
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Back</span>
                        </a>
                    </li>

                    {{-- Breadcrumb menu Products --}}
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('admin.products.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Products
                            </a>
                        </div>
                    </li>

                    {{-- Breadcrumb nama produk --}}
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Judul halaman & tombol edit --}}
            <div class="mt-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <p class="mt-1 text-sm text-gray-500">Product Code: {{ $product->code }}</p>
                </div>

                {{-- Tombol edit produk --}}
                <div class="flex space-x-3">
                    <x-button href="{{ route('admin.products.edit', $product) }}" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit Product
                    </x-button>
                </div>
            </div>
        </div>

        {{-- ================= KONTEN UTAMA ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ================= DETAIL PRODUK ================= --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Informasi dasar produk --}}
                <x-card title="Product Information">
                    <div class="grid grid-cols-2 gap-6">

                        {{-- Kategori produk --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Category</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                <x-badge variant="light">{{ $product->category->name }}</x-badge>
                            </p>
                        </div>

                        {{-- Status ketersediaan produk --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Status</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($product->is_available)
                                    <x-badge variant="success">Available</x-badge>
                                @else
                                    <x-badge variant="danger">Unavailable</x-badge>
                                @endif
                            </p>
                        </div>

                        {{-- Harga produk --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Price</h4>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Stok produk --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Current Stock</h4>
                            <p class="mt-1 text-lg font-semibold {{ $product->stock <= $product->min_stock ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $product->stock }} units
                                @if($product->stock <= $product->min_stock)
                                    <x-badge variant="danger" size="sm" class="ml-2">Low Stock</x-badge>
                                @endif
                            </p>
                        </div>

                        {{-- Batas minimum stok --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Minimum Stock Alert</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->min_stock }} units</p>
                        </div>

                        {{-- Tanggal produk dibuat --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Created At</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $product->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>

                    {{-- Deskripsi produk --}}
                    @if($product->description)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-500">Description</h4>
                            <p class="mt-2 text-sm text-gray-900">{{ $product->description }}</p>
                        </div>
                    @endif
                </x-card>

                {{-- ================= RIWAYAT TRANSAKSI ================= --}}
                <x-card title="Recent Transactions">
                    {{-- Cek apakah produk pernah ditransaksikan --}}
                    @if($product->transactionItems->count() > 0)
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th>Transaction</th>
                                        <th>Date</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    {{-- Menampilkan 10 transaksi terakhir --}}
                                    @foreach($product->transactionItems->take(10) as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.transactions.show', $item->transaction) }}">
                                                {{ $item->transaction->transaction_code }}
                                            </a>
                                        </td>
                                        <td>{{ $item->transaction->created_at->format('d M Y, H:i') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        {{-- Jika belum ada transaksi --}}
                        <div class="text-center py-6">
                            <p class="text-sm text-gray-500">No transactions yet for this product</p>
                        </div>
                    @endif
                </x-card>
            </div>

            {{-- ================= GAMBAR & STATISTIK ================= --}}
            <div class="lg:col-span-1">

                {{-- Gambar produk --}}
                <x-card title="Product Image">
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
                        <img src="{{ asset('images/products/' . str_replace('products/', '', $product->image)) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-auto rounded-lg"
                             onerror="this.onerror=null; this.src='{{ asset('images/products/' . $fallbackImage) }}';">
                    @else
                        <p class="text-center text-gray-500">No image available</p>
                    @endif
                </x-card>

                {{-- Statistik produk --}}
                <x-card title="Statistics" class="mt-6">
                    <dl class="space-y-4">
                        <div>
                            <dt>Total Sold</dt>
                            <dd>{{ $product->transactionItems->sum('quantity') }} units</dd>
                        </div>
                        <div>
                            <dt>Total Revenue</dt>
                            <dd>Rp {{ number_format($product->transactionItems->sum('subtotal'), 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt>Total Transactions</dt>
                            <dd>{{ $product->transactionItems->count() }}</dd>
                        </div>
                    </dl>
                </x-card>
            </div>
        </div>
    </div>
</div>
@endsection
