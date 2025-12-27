@extends('layouts.admin')
{{-- Menggunakan layout admin utama --}}

@section('title', 'Monthly Sales Report')
{{-- Judul halaman Monthly Sales Report --}}

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Container utama halaman --}}

    <!-- Header -->
    {{-- Bagian header laporan --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            {{-- Judul laporan --}}
            <h1 class="text-2xl font-bold text-gray-900">Monthly Sales Report</h1>

            {{-- Periode laporan (bulan & tahun) --}}
            <p class="text-gray-600">
                {{ $reportData['period']['start_date']->format('F Y') }}
            </p>
        </div>

        {{-- Tombol aksi --}}
        <div class="flex space-x-2">
            {{-- Tombol export PDF --}}
            <a href="{{ route('admin.reports.monthly', array_merge(request()->all(), ['format' => 'pdf'])) }}" 
               class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700 transition-colors">
                Export PDF
            </a>

            {{-- Tombol kembali ke halaman reports --}}
            <a href="{{ route('admin.reports.index') }}" 
               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    {{-- Ringkasan data penjualan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Total Revenue --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    {{-- Icon revenue --}}
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">
                        Rp {{ number_format($reportData['summary']['total_revenue'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Total Sales --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    {{-- Icon sales --}}
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Sales</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($reportData['summary']['total_sales']) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Average Order --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    {{-- Icon average order --}}
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Average Order</p>
                    <p class="text-2xl font-bold text-gray-900">
                        Rp {{ number_format($reportData['summary']['average_order_value'], 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Growth --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    {{-- Icon growth --}}
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Growth</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($reportData['summary']['growth_percentage'], 1) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    {{-- Tabel produk terlaris --}}
    @if($reportData['top_products']->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Top Selling Products</h2>

        {{-- Tabel data produk --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                {{-- Header tabel --}}
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity Sold</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                    </tr>
                </thead>

                {{-- Isi tabel --}}
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reportData['top_products']->take(15) as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                {{-- Gambar produk --}}
                                @if(isset($product['image']) && $product['image'])
                                    @php
                                        $fallbackImage = 'placeholder-product.png';
                                        $productName = strtolower($product['name']);
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
                                    <img src="{{ asset('images/products/' . str_replace('products/', '', $product['image'])) }}" 
                                         alt="{{ $product['name'] }}" 
                                         class="h-8 w-8 rounded object-cover mr-3"
                                         onerror="this.onerror=null; this.src='{{ asset('images/products/' . $fallbackImage) }}';">
                                @else
                                    {{-- Placeholder gambar --}}
                                    <div class="h-8 w-8 rounded bg-gray-200 flex items-center justify-center mr-3">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Nama produk --}}
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $product['name'] ?? $product->product_name }}
                                </span>
                            </div>
                        </td>

                        {{-- Jumlah terjual --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($product['sold'] ?? $product->total_sold) }}
                        </td>

                        {{-- Total pendapatan --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Rp {{ number_format($product['revenue'] ?? $product->total_revenue, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@endsection