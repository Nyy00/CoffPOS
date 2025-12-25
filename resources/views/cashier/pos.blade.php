<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS - Kasir | {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}?v={{ time() }}">
</head>
<body class="font-sans antialiased bg-gray-50">
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <x-application-logo class="h-8 w-auto" />
                    <h1 class="text-2xl font-bold text-gray-900">CoffPOS</h1>
                </div>
                <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
                    <span>•</span>
                    <span>Kasir: {{ auth()->user()->name }}</span>
                    <span>•</span>
                    <span id="current-time">{{ now()->format('d M Y, H:i') }}</span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button id="hold-transaction" class="px-4 py-2 bg-gold text-coffee-dark rounded-lg hover:opacity-90 transition-colors font-semibold">
                    📋 Tahan
                </button>
                <button id="view-held-transactions" class="px-4 py-2 bg-coffee-brown text-cream rounded-lg hover:opacity-90 transition-colors font-semibold">
                    📋 Ditahan
                </button>
                <button id="clear-cart" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Kosongkan
                </button>
                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main POS Interface -->
    <div class="pos-grid">
        <!-- Left Panel - Products -->
        <div class="flex flex-col bg-white">
            <!-- Search and Filters -->
            <div class="p-6 border-b bg-cream">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Search Bar -->
                    <div class="flex-1 relative">
                        <input type="text" 
                               id="product-search" 
                               placeholder="Cari produk..." 
                               class="search-input input-enhanced w-full pl-4 pr-4 py-3">
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="lg:w-48">
                        <select id="category-filter" class="input-enhanced w-full py-3 px-4">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Category Tabs -->
            <div class="px-6 py-4 border-b bg-cream">
                <div class="category-tabs">
                    <button class="category-tab active px-4 py-2 rounded-lg whitespace-nowrap font-semibold" data-category="">
                        Semua Produk
                    </button>
                    @foreach($categories as $category)
                        <button class="category-tab px-4 py-2 rounded-lg whitespace-nowrap" data-category="{{ $category->id }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1 p-6 overflow-y-auto custom-scrollbar">
                <div id="products-grid" class="products-grid">
                    @foreach($products as $product)
                        <div class="product-card bg-white rounded-lg shadow-sm border hover:shadow-md transition-all cursor-pointer" 
                             data-product-id="{{ $product->id }}"
                             data-category="{{ $product->category_id }}">
                            <div class="aspect-square relative overflow-hidden rounded-t-lg">
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
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.src='{{ asset('images/products/' . $fallbackImage) }}';">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v2.5a.75.75 0 001.5 0v-2.5a.75.75 0 01.75-.75h4.75a.75.75 0 01.75.75v7a.75.75 0 01-.75.75H5.5a.75.75 0 01-.75-.75v-2.5a.75.75 0 00-1.5 0v2.5A2.25 2.25 0 005.5 16.25h4.75a2.25 2.25 0 002.25-2.25v-7a2.25 2.25 0 00-2.25-2.25zm5.25 3a.75.75 0 01.75.75v9a.75.75 0 01-1.5 0V5.25a.75.75 0 01.75-.75z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Stock Badge -->
                                <div class="absolute top-2 right-2">
                                    @if($product->stock <= 5)
                                        <span class="px-2 py-1 bg-red-500 text-white text-xs rounded-full font-semibold">
                                            {{ $product->stock }} tersisa
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-coffee-brown text-cream text-xs rounded-full font-semibold">
                                            {{ $product->stock }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="p-3">
                                <h3 class="font-medium text-gray-900 text-sm mb-1 line-clamp-2">{{ $product->name }}</h3>
                                <p class="text-xs text-gray-500 mb-2">{{ $product->category->name }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-gold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <button class="add-to-cart-btn w-8 h-8 bg-gold text-coffee-dark rounded-full hover:opacity-90 transition-colors flex items-center justify-center font-bold">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- No Products Found -->
                <div id="no-products" class="hidden text-center py-12">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Produk tidak ditemukan</h3>
                    <p class="text-gray-500">Coba sesuaikan pencarian atau kriteria filter Anda</p>
                </div>
            </div>
        </div>

        <!-- Right Panel - Cart & Checkout -->
        <div class="bg-white border-l flex flex-col">
            <!-- Cart Header -->
            <div class="p-6 border-b bg-cream">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-coffee-dark">🛒 Keranjang</h2>
                    <span id="cart-count" class="px-3 py-1 bg-light-coffee text-coffee-dark rounded-full text-sm font-medium">0 item</span>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <div id="cart-items" class="p-6 space-y-3">
                    <!-- Cart items will be populated by JavaScript -->
                    <div id="empty-cart" class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">🛒</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Keranjang kosong</h3>
                        <p class="text-gray-500">Tambahkan produk untuk memulai transaksi</p>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="border-t p-6 space-y-4">
                <!-- Customer Selection -->
                <div>
                    <label class="block text-sm font-medium text-coffee-dark mb-2">Pelanggan (Opsional)</label>
                    <select id="customer-select" class="input-enhanced w-full py-2 px-3">
                        <option value="">Pelanggan Umum</option>
                        <!-- Customers will be loaded via AJAX -->
                    </select>
                </div>

                <!-- Totals -->
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-coffee-brown">Subtotal:</span>
                        <span id="subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-coffee-brown">Diskon:</span>
                        <span id="discount">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-coffee-brown">Pajak:</span>
                        <span id="tax">Rp 0</span>
                    </div>
                    <div class="border-t border-light-coffee pt-2">
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-coffee-dark">Total:</span>
                            <span id="total" class="text-gold">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-coffee-dark mb-2">Metode Pembayaran</label>
                    <div class="payment-methods">
                        <button class="payment-method active py-2 px-3 border-2 border-gold bg-light-coffee text-coffee-dark rounded-lg text-sm font-medium" data-method="cash">
                            💵 Tunai
                        </button>
                        <button class="payment-method py-2 px-3 border-2 border-light-coffee bg-white text-coffee-dark rounded-lg text-sm font-medium hover:border-gold flex items-center gap-2" data-method="debit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Debit
                        </button>
                        <button class="payment-method py-2 px-3 border-2 border-light-coffee bg-white text-coffee-dark rounded-lg text-sm font-medium hover:border-gold flex items-center gap-2" data-method="credit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Kredit
                        </button>
                        <button class="payment-method py-2 px-3 border-2 border-light-coffee bg-white text-coffee-dark rounded-lg text-sm font-medium hover:border-gold flex items-center gap-2" data-method="digital">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Digital
                        </button>
                    </div>
                </div>

                <!-- Payment Amount (for cash) -->
                <div id="cash-payment" class="space-y-2">
                    <label class="block text-sm font-medium text-coffee-dark">Uang Diterima</label>
                    <input type="number" id="payment-amount" class="input-enhanced w-full py-2 px-3" placeholder="0">
                    <div class="flex justify-between text-sm">
                        <span class="text-coffee-brown">Kembalian:</span>
                        <span id="change" class="font-medium text-gold">Rp 0</span>
                    </div>
                </div>

                <!-- Checkout Button -->
                <button id="checkout-btn" class="checkout-btn w-full py-3 bg-coffee-brown text-cream rounded-lg font-bold disabled:bg-light-coffee disabled:cursor-not-allowed" disabled>
                    💰 Proses Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('cashier.partials.payment-modal')
@include('cashier.partials.receipt-modal')
@include('cashier.partials.hold-transaction-modal')

<!-- Midtrans Snap Script -->
@if(config('midtrans.is_production'))
<script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif

<script src="{{ asset('js/pos.js') }}?v={{ time() }}&r={{ rand() }}"></script>
</body>
</html>
