@extends('layouts.app')
{{-- Menggunakan layout utama aplikasi --}}

@section('title', 'Profit & Loss Analysis')
{{-- Judul halaman analisis laba rugi --}}

@section('content')
<div class="py-6">
    {{-- Wrapper utama halaman --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        {{-- Header halaman analisis --}}
        <div class="mb-6">
            <div class="md:flex md:items-center md:justify-between">
                
                {{-- Judul dan deskripsi --}}
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Profit & Loss Analysis
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Comprehensive financial performance overview
                    </p>
                </div>

                {{-- Filter periode dan tahun --}}
                <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                    {{-- Selector periode laporan --}}
                    <select id="periodSelector" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="7days">Last 7 Days</option>
                        <option value="30days">Last 30 Days</option>
                        <option value="12months" selected>Last 12 Months</option>
                    </select>

                    {{-- Selector tahun --}}
                    <select id="yearSelector" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @for($year = date('Y'); $year >= date('Y') - 3; $year--)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        {{-- Kartu ringkasan (diisi lewat JavaScript) --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6" id="summaryCards">
            <!-- Cards will be populated by JavaScript -->
        </div>

        <!-- Charts Section -->
        {{-- Bagian grafik --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Grafik tren laba rugi --}}
            <x-card title="Profit & Loss Trend">
                <div class="h-80">
                    <canvas id="profitLossChart"></canvas>
                </div>
            </x-card>

            {{-- Grafik perbandingan pendapatan dan pengeluaran --}}
            <x-card title="Revenue vs Expenses">
                <div class="h-80">
                    <canvas id="revenueExpensesChart"></canvas>
                </div>
            </x-card>
        </div>

        <!-- Detailed Analysis -->
        {{-- Analisis detail --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Grafik pembagian pengeluaran per kategori --}}
            <x-card title="Expense Breakdown by Category">
                <div class="h-80">
                    <canvas id="expenseBreakdownChart"></canvas>
                </div>
                <div class="mt-4" id="expenseBreakdownTable">
                    <!-- Table will be populated by JavaScript -->
                </div>
            </x-card>

            {{-- Grafik tren margin keuntungan --}}
            <x-card title="Profit Margin Trend">
                <div class="h-80">
                    <canvas id="profitMarginChart"></canvas>
                </div>
                <div class="mt-4 p-4 bg-gray-50 rounded-lg" id="profitMarginInsights">
                    <!-- Insights will be populated by JavaScript -->
                </div>
            </x-card>
        </div>
    </div>
</div>

{{-- Library Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Deklarasi variabel chart
    let profitLossChart, revenueExpensesChart, expenseBreakdownChart, profitMarginChart;
    
    // Inisialisasi chart
    initializeCharts();
    
    // Load data awal
    loadProfitLossData();
    
    // Event listener perubahan filter
    document.getElementById('periodSelector').addEventListener('change', loadProfitLossData);
    document.getElementById('yearSelector').addEventListener('change', loadProfitLossData);
    
    // Fungsi inisialisasi semua chart
    function initializeCharts() {
        // (kode chart tetap, tidak diubah)
    }

    // Mengambil data laba rugi dari API
    function loadProfitLossData() {
        const period = document.getElementById('periodSelector').value;
        const year = document.getElementById('yearSelector').value;
        
        fetch(`/admin/expenses/profit-loss/api?period=${period}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateSummaryCards(data.summary);
                    updateCharts(data.data, data.summary);
                }
            })
            .catch(error => {
                console.error('Error loading profit/loss data:', error);
            });
    }

    // Mengupdate kartu ringkasan
    function updateSummaryCards(summary) {
        // (isi kartu dirender secara dinamis menggunakan JavaScript)
    }

    // Mengupdate semua grafik
    function updateCharts(data, summary) {
        // Update grafik laba rugi, pendapatan, pengeluaran, dan margin
    }

    // Mengupdate tabel rincian pengeluaran
    function updateExpenseBreakdownTable(expensesByCategory) {
        // Render tabel breakdown pengeluaran
    }

    // Mengupdate insight margin keuntungan
    function updateProfitMarginInsights(summary) {
        // Menampilkan status keuangan dan rekomendasi
    }
});
</script>

@endsection