@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}!</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Today's Revenue -->
            <x-card>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Today's Revenue</dt>
                            <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($stats['today']['revenue'], 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Today's Transactions -->
            <x-card>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Today's Orders</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['today']['transactions'] }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Monthly Revenue -->
            <x-card>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Monthly Revenue</dt>
                            <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($stats['this_month']['revenue'], 0, ',', '.') }}</dd>
                            @if($stats['growth']['revenue'] != 0)
                                <dd class="text-sm {{ $stats['growth']['revenue'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $stats['growth']['revenue'] > 0 ? '+' : '' }}{{ number_format($stats['growth']['revenue'], 1) }}% from last month
                                </dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Monthly Profit -->
            <x-card>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 {{ $stats['profit']['this_month'] >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $stats['profit']['this_month'] >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Monthly Profit</dt>
                            <dd class="text-lg font-medium {{ $stats['profit']['this_month'] >= 0 ? 'text-gray-900' : 'text-red-600' }}">
                                Rp {{ number_format($stats['profit']['this_month'], 0, ',', '.') }}
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.reports.profit-loss') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                        View Detailed Analysis →
                    </a>
                </div>
            </x-card>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Revenue Chart -->
                <x-card title="Revenue Trend (Last 7 Days)">
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </x-card>

                <!-- Recent Transactions -->
                <x-card title="Recent Transactions">
                    @if($recentTransactions->count() > 0)
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                            <a href="{{ route('admin.transactions.show', $transaction) }}">
                                                {{ $transaction->transaction_code }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaction->customer ? $transaction->customer->name : 'Walk-in Customer' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaction->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-center">
                            <x-button href="{{ route('admin.transactions.index') }}" variant="light">
                                View All Transactions
                            </x-button>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-sm text-gray-500">No transactions yet today</p>
                        </div>
                    @endif
                </x-card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <x-card title="Quick Stats">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Products</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['totals']['products'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Customers</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['totals']['customers'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Categories</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['totals']['categories'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">System Users</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $stats['totals']['users'] }}</dd>
                        </div>
                    </dl>
                </x-card>

                <!-- Low Stock Alert -->
                @if($lowStockProducts->count() > 0)
                <x-card title="Low Stock Alert" class="border-l-4 border-red-500">
                    <div class="space-y-3">
                        @foreach($lowStockProducts as $product)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">Stock: {{ $product->stock }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-red-600">{{ $product->stock }} left</p>
                                <p class="text-xs text-gray-500">Category: {{ $product->category->name ?? 'No Category' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <x-button href="{{ route('admin.products.index', ['stock_filter' => 'low']) }}" variant="outline-danger" size="sm" class="w-full">
                            View All Low Stock
                        </x-button>
                    </div>
                </x-card>
                @endif

                <!-- Top Products -->
                @if($chartData['topProducts']->count() > 0)
                <x-card title="Top Products This Month">
                    <div class="space-y-3">
                        @foreach($chartData['topProducts'] as $product)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @if(isset($product['image']) && $product['image'])
                                    <img src="{{ asset('storage/' . $product['image']) }}" 
                                         alt="{{ $product['name'] }}" 
                                         class="h-8 w-8 rounded object-cover">
                                @else
                                    <div class="h-8 w-8 rounded bg-gray-200 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $product['name'] }}</p>
                                    <p class="text-xs text-gray-500">Stock: {{ $product['stock'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $product['sold'] }} sold</p>
                                <p class="text-xs text-gray-500">Rp {{ number_format($product['revenue'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </x-card>
                @endif

                <!-- Quick Actions -->
                <x-card title="Quick Actions">
                    <div class="space-y-3">
                        <x-button href="{{ route('admin.products.create') }}" variant="primary" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Product
                        </x-button>
                        <x-button href="{{ route('admin.customers.create') }}" variant="light" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Add Customer
                        </x-button>
                        <x-button href="{{ route('admin.expenses.create') }}" variant="light" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Record Expense
                        </x-button>
                        <x-button href="{{ route('admin.reports.profit-loss') }}" variant="outline" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            P&L Analysis
                        </x-button>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<!-- Chart Data Global -->
<script>
    // Expose chart data to window for JavaScript access
    window.chartDataGlobal = {
        revenue: @json($chartData['revenue'] ?? []),
        sales: @json($chartData['sales'] ?? []),
        topProducts: @json($chartData['topProducts'] ?? []),
        paymentMethods: @json($chartData['paymentMethods'] ?? [])
    };

    console.log('Chart data loaded:', window.chartDataGlobal);
</script>

<!-- Revenue Chart Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Wait a bit for Chart.js to be fully loaded
        setTimeout(() => {
            const ctx = document.getElementById('revenueChart');
            if (!ctx || typeof Chart === 'undefined') {
                console.error('Chart canvas or Chart.js not found');
                return;
            }

            const revenueData = window.chartDataGlobal?.revenue || [];
            
            if (!revenueData || revenueData.length === 0) {
                console.warn('Revenue data is empty');
                // Display placeholder message
                ctx.parentElement.innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500">No revenue data available</div>';
                return;
            }

            try {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: revenueData.map(item => item.label || item.date || 'N/A'),
                        datasets: [{
                            label: 'Revenue',
                            data: revenueData.map(item => item.value || item.revenue || 0),
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#FFFFFF',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#1E40AF'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleColor: '#FFFFFF',
                                bodyColor: '#FFFFFF',
                                borderColor: '#3B82F6',
                                borderWidth: 1,
                                callbacks: {
                                    label: function(context) {
                                        const value = context.parsed.y;
                                        return 'Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    },
                                    title: function(tooltipItems) {
                                        return tooltipItems[0].label || '';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                        }
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                    },
                                    font: {
                                        size: 11
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)',
                                    drawBorder: false
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        }
                    }
                });

                console.log('✅ Revenue chart initialized successfully');
            } catch (error) {
                console.error('❌ Error initializing revenue chart:', error);
            }
        }, 100);
    });
</script>

<!-- Top Products Chart Script (if exists) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const ctx = document.getElementById('topProductsChart');
            if (!ctx || typeof Chart === 'undefined') {
                console.log('Top products chart not found or Chart.js unavailable');
                return;
            }

            const productsData = window.chartDataGlobal?.topProducts || [];
            
            if (!productsData || productsData.length === 0) {
                console.warn('Top products data is empty');
                return;
            }

            try {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: productsData.map(item => item.name || 'N/A'),
                        datasets: [{
                            data: productsData.map(item => item.sold || item.total_sold || 0),
                            backgroundColor: [
                                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', 
                                '#8B5CF6', '#06B6D4', '#84CC16', '#F97316'
                            ],
                            borderWidth: 2,
                            borderColor: '#FFFFFF'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    font: { size: 12 }
                                }
                            }
                        }
                    }
                });

                console.log('✅ Top products chart initialized successfully');
            } catch (error) {
                console.error('❌ Error initializing top products chart:', error);
            }
        }, 100);
    });
</script>

@endsection