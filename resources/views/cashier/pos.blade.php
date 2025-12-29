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
    <div class="bg-white shadow-sm border-b px-3 sm:px-6 py-3 sm:py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 sm:space-x-4">
                <div class="flex items-center space-x-2">
                    <x-application-logo class="h-6 sm:h-8 w-auto" />
                    <h1 class="text-lg sm:text-2xl font-bold text-gray-900">CoffPOS</h1>
                </div>
                <div class="hidden lg:flex items-center space-x-2 text-sm text-gray-500">
                    <span>•</span>
                    <span>Kasir: {{ auth()->user()->name }}</span>
                    <span>•</span>
                    <span id="current-time">{{ now()->format('d M Y, H:i') }}</span>
                </div>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-btn" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            
            <!-- Desktop Actions -->
            <div class="hidden md:flex items-center space-x-2 lg:space-x-3">
                <button id="hold-transaction" class="px-2 lg:px-4 py-2 bg-gold text-coffee-dark rounded-lg hover:opacity-90 transition-colors font-semibold text-sm lg:text-base">
                    <span class="hidden lg:inline">📋 Tahan</span>
                    <span class="lg:hidden">📋</span>
                </button>
                <button id="view-held-transactions" class="px-2 lg:px-4 py-2 bg-coffee-brown text-cream rounded-lg hover:opacity-90 transition-colors font-semibold text-sm lg:text-base">
                    <span class="hidden lg:inline">📋 Ditahan</span>
                    <span class="lg:hidden">📋</span>
                </button>
                <button id="clear-cart" class="px-2 lg:px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center gap-1 lg:gap-2 text-sm lg:text-base">
                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span class="hidden lg:inline">Kosongkan</span>
                </button>
                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="px-2 lg:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-1 lg:gap-2 text-sm lg:text-base" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="hidden lg:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-3 pt-3 border-t border-gray-200">
            <div class="flex flex-wrap gap-2">
                <button id="hold-transaction-mobile" class="flex-1 px-3 py-2 bg-gold text-coffee-dark rounded-lg hover:opacity-90 transition-colors font-semibold text-sm">
                    📋 Tahan
                </button>
                <button id="view-held-transactions-mobile" class="flex-1 px-3 py-2 bg-coffee-brown text-cream rounded-lg hover:opacity-90 transition-colors font-semibold text-sm">
                    📋 Ditahan
                </button>
                <button id="clear-cart-mobile" class="flex-1 px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                    🗑️ Kosongkan
                </button>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        🚪 Keluar
                    </button>
                </form>
            </div>
            <div class="mt-2 text-xs text-gray-500 text-center">
                Kasir: {{ auth()->user()->name }} • {{ now()->format('d M Y, H:i') }}
            </div>
        </div>
    </div>

    <!-- Main POS Interface -->
    <div class="flex flex-col lg:flex-row h-full">
        <!-- Mobile Cart Toggle Button -->
        <div class="lg:hidden bg-white border-b px-4 py-2">
            <button id="toggle-cart" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Lihat Keranjang (<span id="mobile-cart-count">0</span>)
            </button>
        </div>
        
        <!-- Left Panel - Products -->
        <div class="flex-1 flex flex-col bg-white lg:border-r">
            <!-- Search and Filters -->
            <div class="p-3 sm:p-6 border-b bg-cream">
                <div class="flex flex-col gap-3 sm:gap-4">
                    <!-- Search Bar -->
                    <div class="relative">
                        <input type="text" 
                               id="product-search" 
                               placeholder="Cari produk..." 
                               class="search-input input-enhanced w-full pl-4 pr-4 py-2 sm:py-3 text-sm sm:text-base">
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="w-full">
                        <select id="category-filter" class="input-enhanced w-full py-2 sm:py-3 px-4 text-sm sm:text-base">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Category Tabs -->
            <div class="px-3 sm:px-6 py-2 sm:py-4 border-b bg-cream">
                <div class="category-tabs overflow-x-auto">
                    <button class="category-tab active px-3 sm:px-4 py-2 rounded-lg whitespace-nowrap font-semibold text-sm sm:text-base" data-category="">
                        Semua Produk
                    </button>
                    @foreach($categories as $category)
                        <button class="category-tab px-3 sm:px-4 py-2 rounded-lg whitespace-nowrap text-sm sm:text-base" data-category="{{ $category->id }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1 p-3 sm:p-6 overflow-y-auto custom-scrollbar">
                <div id="products-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
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
                                        <svg class="w-8 h-8 sm:w-12 sm:h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v2.5a.75.75 0 001.5 0v-2.5a.75.75 0 01.75-.75h4.75a.75.75 0 01.75.75v7a.75.75 0 01-.75.75H5.5a.75.75 0 01-.75-.75v-2.5a.75.75 0 00-1.5 0v2.5A2.25 2.25 0 005.5 16.25h4.75a2.25 2.25 0 002.25-2.25v-7a2.25 2.25 0 00-2.25-2.25zm5.25 3a.75.75 0 01.75.75v9a.75.75 0 01-1.5 0V5.25a.75.75 0 01.75-.75z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Stock Badge -->
                                <div class="absolute top-1 sm:top-2 right-1 sm:right-2">
                                    @if($product->stock <= 5)
                                        <span class="px-1 sm:px-2 py-0.5 sm:py-1 bg-red-500 text-white text-xs rounded-full font-semibold">
                                            {{ $product->stock }}
                                        </span>
                                    @else
                                        <span class="px-1 sm:px-2 py-0.5 sm:py-1 bg-coffee-brown text-cream text-xs rounded-full font-semibold">
                                            {{ $product->stock }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="p-2 sm:p-3">
                                <h3 class="font-medium text-gray-900 text-xs sm:text-sm mb-1 line-clamp-2">{{ $product->name }}</h3>
                                <p class="text-xs text-gray-500 mb-2 hidden sm:block">{{ $product->category->name }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm sm:text-lg font-bold text-gold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <button class="add-to-cart-btn w-6 h-6 sm:w-8 sm:h-8 bg-gold text-coffee-dark rounded-full hover:opacity-90 transition-colors flex items-center justify-center font-bold text-sm sm:text-base">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- No Products Found -->
                <div id="no-products" class="hidden text-center py-8 sm:py-12">
                    <svg class="w-16 h-16 sm:w-24 sm:h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Produk tidak ditemukan</h3>
                    <p class="text-sm text-gray-500">Coba sesuaikan pencarian atau kriteria filter Anda</p>
                </div>
            </div>
        </div>

        <!-- Right Panel - Cart & Checkout -->
        <div id="cart-panel" class="hidden lg:flex lg:w-80 xl:w-96 bg-white border-l flex-col" style="height: calc(100vh - 64px);">
            <!-- Cart Header -->
            <div class="p-4 sm:p-6 border-b bg-cream flex-shrink-0">
                <div class="flex items-center justify-between">
                    <h2 class="text-base sm:text-lg font-semibold text-coffee-dark">🛒 Keranjang</h2>
                    <div class="flex items-center gap-2">
                        <span id="cart-count" class="px-2 sm:px-3 py-1 bg-light-coffee text-coffee-dark rounded-full text-xs sm:text-sm font-medium">0 item</span>
                        <button id="close-cart" class="lg:hidden p-1 text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cart Items - Scrollable -->
            <div class="flex-1 overflow-y-auto custom-scrollbar" style="max-height: calc(100vh - 400px);">
                <div id="cart-items" class="p-4 space-y-3">
                    <!-- Cart items will be populated by JavaScript -->
                    <div id="empty-cart" class="text-center py-8">
                        <div class="text-gray-400 text-4xl mb-3">🛒</div>
                        <h3 class="text-base font-medium text-gray-900 mb-2">Keranjang kosong</h3>
                        <p class="text-sm text-gray-500">Tambahkan produk untuk memulai transaksi</p>
                    </div>
                </div>
            </div>

            <!-- Cart Summary - Fixed at bottom -->
            <div class="border-t p-4 space-y-3 bg-white" style="flex-shrink: 0;">
                <!-- Customer Selection -->
                <div>
                    <label class="block text-sm font-medium text-coffee-dark mb-2">Pelanggan (Opsional)</label>
                    <select id="customer-select" class="input-enhanced w-full py-2 px-3 text-sm">
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
                        <div class="flex justify-between text-base font-bold">
                            <span class="text-coffee-dark">Total:</span>
                            <span id="total" class="text-gold">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-coffee-dark mb-2">Metode Pembayaran</label>
                    <div class="payment-methods grid grid-cols-2 gap-2">
                        <button class="payment-method active py-2 px-2 border-2 border-gold bg-light-coffee text-coffee-dark rounded-lg text-xs font-medium" data-method="cash">
                            💵 Tunai
                        </button>
                        <button class="payment-method py-2 px-2 border-2 border-light-coffee bg-white text-coffee-dark rounded-lg text-xs font-medium hover:border-gold flex items-center justify-center gap-1" data-method="debit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Debit
                        </button>
                        <button class="payment-method py-2 px-2 border-2 border-light-coffee bg-white text-coffee-dark rounded-lg text-xs font-medium hover:border-gold flex items-center justify-center gap-1" data-method="credit">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Kredit
                        </button>
                        <button class="payment-method py-2 px-2 border-2 border-light-coffee bg-white text-coffee-dark rounded-lg text-xs font-medium hover:border-gold flex items-center justify-center gap-1" data-method="digital">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Digital
                        </button>
                    </div>
                </div>

                <!-- Payment Amount (for cash) -->
                <div id="cash-payment" class="space-y-2">
                    <label class="block text-sm font-medium text-coffee-dark">Uang Diterima</label>
                    <input type="number" id="payment-amount" class="input-enhanced w-full py-2 px-3 text-sm" placeholder="0">
                    <div class="flex justify-between text-sm">
                        <span class="text-coffee-brown">Kembalian:</span>
                        <span id="change" class="font-medium text-gold">Rp 0</span>
                    </div>
                </div>

                <!-- Checkout Button -->
                <button id="checkout-btn" class="checkout-btn w-full py-3 bg-coffee-brown text-cream rounded-lg font-bold text-sm disabled:bg-light-coffee disabled:cursor-not-allowed" disabled>
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

<!-- Suppress Midtrans console errors (non-critical) -->
<script>
// Suppress non-critical Midtrans network errors
const originalConsoleError = console.error;
console.error = function(...args) {
    const message = args.join(' ');
    // Skip Midtrans network tracking errors (non-critical)
    if (message.includes('Network: Network Error: Failed to fetch') || 
        message.includes('snap-assets') ||
        message.includes('Midtrans') && message.includes('Failed to fetch')) {
        return; // Suppress these specific errors
    }
    // Log other errors normally
    originalConsoleError.apply(console, args);
};
</script>

<script src="{{ asset('js/pos.js') }}?v={{ time() }}&r={{ rand() }}"></script>

<!-- Mobile POS JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Mobile cart toggle
    const toggleCartBtn = document.getElementById('toggle-cart');
    const cartPanel = document.getElementById('cart-panel');
    const closeCartBtn = document.getElementById('close-cart');
    const mobileCartCount = document.getElementById('mobile-cart-count');
    
    if (toggleCartBtn && cartPanel) {
        toggleCartBtn.addEventListener('click', function() {
            cartPanel.classList.remove('hidden');
            cartPanel.classList.add('fixed', 'inset-0', 'z-50', 'lg:relative', 'lg:inset-auto', 'lg:z-auto');
            // Ensure proper height and flex structure for mobile
            cartPanel.style.height = '100vh';
            cartPanel.style.maxHeight = '100vh';
            cartPanel.style.display = 'flex';
            cartPanel.style.flexDirection = 'column';
        });
    }
    
    if (closeCartBtn && cartPanel) {
        closeCartBtn.addEventListener('click', function() {
            cartPanel.classList.add('hidden');
            cartPanel.classList.remove('fixed', 'inset-0', 'z-50');
        });
    }
    
    // Sync mobile cart count with main cart count
    const mainCartCount = document.getElementById('cart-count');
    if (mainCartCount && mobileCartCount) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' || mutation.type === 'characterData') {
                    mobileCartCount.textContent = mainCartCount.textContent.replace(' item', '');
                }
            });
        });
        
        observer.observe(mainCartCount, {
            childList: true,
            subtree: true,
            characterData: true
        });
    }
    
    // Mobile button handlers
    const mobileButtons = {
        'hold-transaction-mobile': 'hold-transaction',
        'view-held-transactions-mobile': 'view-held-transactions',
        'clear-cart-mobile': 'clear-cart'
    };
    
    Object.entries(mobileButtons).forEach(([mobileId, desktopId]) => {
        const mobileBtn = document.getElementById(mobileId);
        const desktopBtn = document.getElementById(desktopId);
        
        if (mobileBtn && desktopBtn) {
            mobileBtn.addEventListener('click', function() {
                desktopBtn.click();
            });
        }
    });
    
    // Auto-hide mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (mobileMenu && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
            mobileMenu.classList.add('hidden');
        }
    });
    
    // Handle screen resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) { // lg breakpoint
            if (cartPanel) {
                cartPanel.classList.remove('hidden', 'fixed', 'inset-0', 'z-50');
                cartPanel.classList.add('lg:flex');
                // Reset height for desktop
                cartPanel.style.height = 'calc(100vh - 64px)';
            }
        } else {
            if (cartPanel && !cartPanel.classList.contains('fixed')) {
                cartPanel.classList.add('hidden');
            }
        }
    });
    
    // Ensure proper cart scrolling on mobile
    if (cartPanel) {
        const cartItems = cartPanel.querySelector('#cart-items');
        if (cartItems) {
            // Add touch scrolling support
            cartItems.style.webkitOverflowScrolling = 'touch';
            cartItems.style.overscrollBehavior = 'contain';
        }
    }
});
</script>
</body>
</html>
