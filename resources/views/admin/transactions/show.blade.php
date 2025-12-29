@extends('layouts.app')
{{-- Menggunakan layout utama aplikasi --}}

@section('title', 'Transaction Details - ' . $transaction->transaction_code)
{{-- Judul halaman detail transaksi --}}

@section('content')
<div class="py-6">
    {{-- Wrapper utama halaman --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6">
            {{-- Breadcrumb navigasi --}}
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">

                    {{-- Tombol kembali ke halaman transaksi --}}
                    <li>
                        <a href="{{ route('admin.transactions.index') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Back</span>
                        </a>
                    </li>

                    {{-- Breadcrumb ke halaman Transaksi --}}
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('admin.transactions.index') }}"
                               class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Transaksi
                            </a>
                        </div>
                    </li>

                    {{-- Breadcrumb halaman aktif --}}
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">
                                {{ $transaction->transaction_code }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Transaction Details -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            {{-- Informasi utama transaksi --}}
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg font-medium text-gray-900">Transaction Details</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Detail lengkap transaksi {{ $transaction->transaction_code }}
                </p>
            </div>

            {{-- Data transaksi --}}
            <div class="border-t border-gray-200">
                <dl>
                    {{-- Kode transaksi --}}
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Transaction Code</dt>
                        <dd class="text-sm text-gray-900 sm:col-span-2">
                            {{ $transaction->transaction_code }}
                        </dd>
                    </div>

                    {{-- Tanggal dan waktu transaksi --}}
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                        <dd class="text-sm text-gray-900 sm:col-span-2">
                            {{ $transaction->created_at->format('d M Y, H:i:s') }}
                        </dd>
                    </div>

                    {{-- Kasir --}}
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Cashier</dt>
                        <dd class="text-sm text-gray-900 sm:col-span-2">
                            {{ $transaction->user->name }}
                        </dd>
                    </div>

                    {{-- Customer --}}
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Customer</dt>
                        <dd class="text-sm text-gray-900 sm:col-span-2">
                            @if($transaction->customer)
                                <a href="{{ route('admin.customers.show', $transaction->customer) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    {{ $transaction->customer->name }}
                                </a>
                                <span class="text-gray-500">
                                    - {{ $transaction->customer->phone }}
                                </span>
                            @else
                                <span class="text-gray-500">Walk-in Customer</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Transaction Items -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Item</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Produk yang dibeli dalam transaksi ini</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transaction->transactionItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($item->product && $item->product->image)
                                            @php
                                                $fallbackImage = 'placeholder-product.png';
                                                $productName = strtolower($item->product->name);
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
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" 
                                                     src="{{ asset('images/products/' . str_replace('products/', '', $item->product->image)) }}" 
                                                     alt="{{ $item->product->name }}"
                                                     onerror="this.onerror=null; this.src='{{ asset('images/products/' . $fallbackImage) }}';">
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                @if($item->product)
                                                    <a href="{{ route('admin.products.show', $item->product) }}" class="text-blue-600 hover:text-blue-900">
                                                        {{ $item->product->name }}
                                                    </a>
                                                @else
                                                    {{ $item->product_name }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->product->category->name ?? 'No Category' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection