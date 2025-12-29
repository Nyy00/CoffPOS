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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js library failed to load');
        return;
    }

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
        // Initialize Profit & Loss Trend Chart
        const profitLossCtx = document.getElementById('profitLossChart');
        if (profitLossCtx) {
            profitLossChart = new Chart(profitLossCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Profit',
                        data: [],
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize Revenue vs Expenses Chart
        const revenueExpensesCtx = document.getElementById('revenueExpensesChart');
        if (revenueExpensesCtx) {
            revenueExpensesChart = new Chart(revenueExpensesCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Revenue',
                        data: [],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }, {
                        label: 'Expenses',
                        data: [],
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize Expense Breakdown Chart
        const expenseBreakdownCtx = document.getElementById('expenseBreakdownChart');
        if (expenseBreakdownCtx) {
            expenseBreakdownChart = new Chart(expenseBreakdownCtx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            '#ef4444', '#f97316', '#eab308', '#22c55e',
                            '#06b6d4', '#3b82f6', '#8b5cf6', '#ec4899'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Initialize Profit Margin Chart
        const profitMarginCtx = document.getElementById('profitMarginChart');
        if (profitMarginCtx) {
            profitMarginChart = new Chart(profitMarginCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Profit Margin (%)',
                        data: [],
                        borderColor: 'rgb(168, 85, 247)',
                        backgroundColor: 'rgba(168, 85, 247, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Mengambil data laba rugi dari API
    function loadProfitLossData() {
        const period = document.getElementById('periodSelector').value;
        const year = document.getElementById('yearSelector').value;
        
        // Show loading state
        showLoadingState();
        
        fetch(`/admin/expenses/profit-loss/api?period=${period}&year=${year}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateSummaryCards(data.summary);
                    updateCharts(data.data, data.summary);
                } else {
                    throw new Error('API returned error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error loading profit/loss data:', error);
                showErrorState(error.message);
            });
    }

    // Show loading state
    function showLoadingState() {
        const summaryCards = document.getElementById('summaryCards');
        if (summaryCards) {
            summaryCards.innerHTML = '<div class="col-span-4 text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div><p class="mt-2 text-gray-500">Loading data...</p></div>';
        }
    }

    // Show error state
    function showErrorState(message) {
        const summaryCards = document.getElementById('summaryCards');
        if (summaryCards) {
            summaryCards.innerHTML = `<div class="col-span-4 text-center py-8"><div class="text-red-500 mb-2">⚠️</div><p class="text-red-600">Error loading data: ${message}</p><button onclick="loadProfitLossData()" class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Retry</button></div>`;
        }
    }

    // Mengupdate kartu ringkasan
    function updateSummaryCards(summary) {
        const summaryCards = document.getElementById('summaryCards');
        if (!summaryCards) return;

        summaryCards.innerHTML = `
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                <dd class="text-lg font-medium text-gray-900">Rp ${summary.total_revenue.toLocaleString('id-ID')}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Expenses</dt>
                                <dd class="text-lg font-medium text-gray-900">Rp ${summary.total_expenses.toLocaleString('id-ID')}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 ${summary.total_profit >= 0 ? 'bg-green-500' : 'bg-red-500'} rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Net Profit</dt>
                                <dd class="text-lg font-medium ${summary.total_profit >= 0 ? 'text-green-600' : 'text-red-600'}">Rp ${summary.total_profit.toLocaleString('id-ID')}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 ${summary.profit_margin >= 0 ? 'bg-blue-500' : 'bg-red-500'} rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Profit Margin</dt>
                                <dd class="text-lg font-medium ${summary.profit_margin >= 0 ? 'text-blue-600' : 'text-red-600'}">${summary.profit_margin.toFixed(1)}%</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Mengupdate semua grafik
    function updateCharts(data, summary) {
        if (!data || data.length === 0) {
            console.warn('No data available for charts');
            return;
        }

        const labels = data.map(item => item.label);
        const revenues = data.map(item => item.revenue);
        const expenses = data.map(item => item.expenses);
        const profits = data.map(item => item.profit);
        const profitMargins = data.map(item => item.profit_margin);

        // Update Profit & Loss Chart
        if (profitLossChart) {
            profitLossChart.data.labels = labels;
            profitLossChart.data.datasets[0].data = profits;
            profitLossChart.update();
        }

        // Update Revenue vs Expenses Chart
        if (revenueExpensesChart) {
            revenueExpensesChart.data.labels = labels;
            revenueExpensesChart.data.datasets[0].data = revenues;
            revenueExpensesChart.data.datasets[1].data = expenses;
            revenueExpensesChart.update();
        }

        // Update Expense Breakdown Chart
        if (expenseBreakdownChart && summary.expenses_by_category) {
            const categories = Object.keys(summary.expenses_by_category);
            const amounts = Object.values(summary.expenses_by_category);
            
            expenseBreakdownChart.data.labels = categories;
            expenseBreakdownChart.data.datasets[0].data = amounts;
            expenseBreakdownChart.update();
            
            updateExpenseBreakdownTable(summary.expenses_by_category);
        }

        // Update Profit Margin Chart
        if (profitMarginChart) {
            profitMarginChart.data.labels = labels;
            profitMarginChart.data.datasets[0].data = profitMargins;
            profitMarginChart.update();
        }

        updateProfitMarginInsights(summary);
    }

    // Mengupdate tabel rincian pengeluaran
    function updateExpenseBreakdownTable(expensesByCategory) {
        const tableContainer = document.getElementById('expenseBreakdownTable');
        if (!tableContainer || !expensesByCategory) return;

        const total = Object.values(expensesByCategory).reduce((sum, amount) => sum + amount, 0);
        
        let tableHTML = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
        `;

        Object.entries(expensesByCategory).forEach(([category, amount]) => {
            const percentage = total > 0 ? ((amount / total) * 100).toFixed(1) : 0;
            tableHTML += `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${category}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp ${amount.toLocaleString('id-ID')}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${percentage}%</td>
                </tr>
            `;
        });

        tableHTML += `
                    </tbody>
                </table>
            </div>
        `;

        tableContainer.innerHTML = tableHTML;
    }

    // Mengupdate insight margin keuntungan
    function updateProfitMarginInsights(summary) {
        const insightsContainer = document.getElementById('profitMarginInsights');
        if (!insightsContainer) return;

        let status = '';
        let recommendation = '';
        let statusColor = '';

        if (summary.profit_margin >= 20) {
            status = 'Excellent';
            statusColor = 'text-green-600';
            recommendation = 'Your profit margin is excellent. Consider expanding operations or investing in growth.';
        } else if (summary.profit_margin >= 10) {
            status = 'Good';
            statusColor = 'text-blue-600';
            recommendation = 'Your profit margin is healthy. Look for opportunities to optimize costs further.';
        } else if (summary.profit_margin >= 5) {
            status = 'Fair';
            statusColor = 'text-yellow-600';
            recommendation = 'Your profit margin is acceptable but could be improved. Review your pricing and cost structure.';
        } else if (summary.profit_margin >= 0) {
            status = 'Poor';
            statusColor = 'text-orange-600';
            recommendation = 'Your profit margin is low. Consider reducing costs or increasing prices.';
        } else {
            status = 'Loss';
            statusColor = 'text-red-600';
            recommendation = 'You are operating at a loss. Immediate action is needed to reduce costs or increase revenue.';
        }

        insightsContainer.innerHTML = `
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Financial Health</h4>
                    <p class="text-lg font-semibold ${statusColor}">${status}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Current Margin</p>
                    <p class="text-lg font-semibold ${statusColor}">${summary.profit_margin.toFixed(1)}%</p>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-sm text-gray-600">${recommendation}</p>
            </div>
        `;
    }
});
</script>

@endsection