@extends('layouts.app')
{{-- Menggunakan layout utama aplikasi --}}

@section('title', 'Reports')
{{-- Judul halaman laporan --}}

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Container utama halaman reports --}}

    <div class="flex justify-between items-center mb-6">
        {{-- Header halaman --}}
        <h1 class="text-3xl font-bold text-gray-900">Reports</h1>
    </div>

    <!-- Reports Grid -->
    {{-- Grid daftar jenis laporan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Daily Sales Report -->
        {{-- Kartu laporan penjualan harian --}}
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="p-6">
                {{-- Header kartu --}}
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-full">
                        {{-- Icon --}}
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Daily Sales Report</h3>
                        <p class="text-sm text-gray-600">Daily sales performance and statistics</p>
                    </div>
                </div>

                {{-- Tombol aksi --}}
                <div class="flex space-x-2">
                    <a href="{{ route('admin.reports.daily') }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium text-center">
                        View Report
                    </a>
                    <a href="{{ route('admin.reports.daily', ['format' => 'pdf']) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                        PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Monthly Sales Report -->
        {{-- Kartu laporan penjualan bulanan --}}
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Monthly Sales Report</h3>
                        <p class="text-sm text-gray-600">Monthly sales trends and analysis</p>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('admin.reports.monthly') }}" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium text-center">
                        View Report
                    </a>
                    <a href="{{ route('admin.reports.monthly', ['format' => 'pdf']) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                        PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Performance Report -->
        {{-- Kartu laporan performa produk --}}
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Product Performance</h3>
                        <p class="text-sm text-gray-600">Top selling products and analysis</p>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('admin.reports.products') }}" class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium text-center">
                        View Report
                    </a>
                    <a href="{{ route('admin.reports.products', ['format' => 'pdf']) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium">
                        PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- Kartu laporan lainnya (Stock, Profit & Loss, Custom Report)
             mengikuti pola yang sama --}}
    </div>

    <!-- Quick Stats -->
    {{-- Statistik singkat laporan --}}
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Statistics</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Statistik transaksi hari ini --}}
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ number_format(rand(50, 200)) }}</div>
                <div class="text-sm text-gray-600">Today's Transactions</div>
            </div>

            {{-- Statistik pendapatan --}}
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">
                    Rp {{ number_format(rand(5000000, 15000000), 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-600">Today's Revenue</div>
            </div>

            {{-- Statistik item terjual --}}
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ number_format(rand(800, 1500)) }}</div>
                <div class="text-sm text-gray-600">Items Sold Today</div>
            </div>

            {{-- Statistik stok rendah --}}
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">{{ number_format(rand(5, 25)) }}</div>
                <div class="text-sm text-gray-600">Low Stock Items</div>
            </div>
        </div>
    </div>
</div>

{{-- Modal custom report --}}
<div id="customReportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    {{-- Isi modal --}}
</div>

{{-- Script buka & tutup modal --}}
<script>
function openCustomReportModal() {
    document.getElementById('customReportModal').classList.remove('hidden');
}

function closeCustomReportModal() {
    document.getElementById('customReportModal').classList.add('hidden');
}
</script>

@endsection