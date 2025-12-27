@extends('layouts.app')
{{-- Menggunakan layout utama aplikasi --}}

@section('title', 'Daily Sales Report')
{{-- Judul halaman --}}

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Container utama halaman laporan --}}

    <div class="flex justify-between items-center mb-6">
        {{-- Header laporan --}}
        <div>
            {{-- Judul laporan --}}
            <h1 class="text-3xl font-bold text-gray-900">Daily Sales Report</h1>

            {{-- Tanggal laporan --}}
            <p class="text-gray-600 mt-1">
                {{ $reportData['date']->format('F j, Y') }}
            </p>
        </div>

        {{-- Filter tanggal & export --}}
        <div class="flex space-x-3">
            {{-- Form filter tanggal --}}
            <form method="GET" class="flex items-center space-x-2">
                <input 
                    type="date" 
                    name="date"
                    value="{{ request('date', $reportData['date']->format('Y-m-d')) }}"
                    class="border border-gray-300 rounded-md px-3 py-2"
                >
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md">
                    Update
                </button>
            </form>

            {{-- Tombol export PDF --}}
            <a href="{{ route('admin.reports.daily', array_merge(request()->all(), ['format' => 'pdf'])) }}"
               class="bg-red-600 text-white px-4 py-2 rounded-md">
                Export PDF
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    {{-- Ringkasan data penjualan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Total Revenue --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600">Total Revenue</p>
            <p class="text-2xl font-bold">
                Rp {{ number_format($reportData['summary']['total_revenue'], 0, ',', '.') }}
            </p>
        </div>

        {{-- Total Transaksi --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600">Transactions</p>
            <p class="text-2xl font-bold">
                {{ number_format($reportData['summary']['total_transactions']) }}
            </p>
        </div>

        {{-- Total Item Terjual --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600">Items Sold</p>
            <p class="text-2xl font-bold">
                {{ number_format($reportData['summary']['total_items']) }}
            </p>
        </div>

        {{-- Net Profit --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-sm text-gray-600">Net Profit</p>
            <p class="text-2xl font-bold">
                Rp {{ number_format($reportData['summary']['net_profit'], 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Payment Methods -->
    {{-- Statistik metode pembayaran --}}
    @if($reportData['payment_methods']->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Payment Methods</h2>

        <table class="min-w-full">
            @foreach($reportData['payment_methods'] as $method => $stats)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $method)) }}</td>
                <td>{{ number_format($stats['count']) }}</td>
                <td>Rp {{ number_format($stats['total'], 0, ',', '.') }}</td>
                <td>{{ $stats['percentage'] }}%</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Top Products -->
    {{-- Produk paling laku --}}
    @if($reportData['top_products']->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Top Selling Products</h2>

        <table class="min-w-full">
            @foreach($reportData['top_products']->take(10) as $product)
            <tr>
                <td>{{ $product['name'] ?? $product->product_name }}</td>
                <td>{{ number_format($product['sold'] ?? $product->total_sold) }}</td>
                <td>Rp {{ number_format($product['revenue'] ?? $product->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Recent Transactions -->
    {{-- Daftar transaksi terbaru --}}
    @if($reportData['transactions']->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Transactions</h2>

        <table class="min-w-full">
            @foreach($reportData['transactions']->take(20) as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('H:i') }}</td>
                <td>{{ $transaction->transaction_code }}</td>
                <td>{{ $transaction->customer ? $transaction->customer->name : 'Walk-in' }}</td>
                <td>{{ $transaction->transactionItems->sum('quantity') }}</td>
                <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

</div>
@endsection
