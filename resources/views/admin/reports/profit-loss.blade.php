@extends('layouts.app')

@section('title', 'Profit & Loss Analysis')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Profit & Loss Analysis
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Comprehensive financial performance overview
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                    <select id="periodSelector" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="7days">Last 7 Days</option>
                        <option value="30days">Last 30 Days</option>
                        <option value="12months" selected>Last 12 Months</option>
                    </select>
                    <select id="yearSelector" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @for($year = date('Y'); $year >= date('Y') - 3; $year--)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6" id="summaryCards">
            <!-- Cards will be populated by JavaScript -->
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Profit/Loss Trend Chart -->
            <x-card title="Profit & Loss Trend">
                <div class="h-80">
                    <canvas id="profitLossChart"></canvas>
                </div>
            </x-card>

            <!-- Revenue vs Expenses Chart -->
            <x-card title="Revenue vs Expenses">
                <div class="h-80">
                    <canvas id="revenueExpensesChart"></canvas>
                </div>
            </x-card>
        </div>

        <!-- Detailed Analysis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Expense Breakdown -->
            <x-card title="Expense Breakdown by Category">
                <div class="h-80">
                    <canvas id="expenseBreakdownChart"></canvas>
                </div>
                <div class="mt-4" id="expenseBreakdownTable">
                    <!-- Table will be populated by JavaScript -->
                </div>
            </x-card>

            <!-- Profit Margin Analysis -->
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let profitLossChart, revenueExpensesChart, expenseBreakdownChart, profitMarginChart;
    
    // Initialize charts
    initializeCharts();
    
    // Load initial data
    loadProfitLossData();
    
    // Event listeners
    document.getElementById('periodSelector').addEventListener('change', loadProfitLossData);
    document.getElementById('yearSelector').addEventListener('change', loadProfitLossData);
    
    function initializeCharts() {
        // Profit/Loss Trend Chart
        const profitLossCtx = document.getElementById('profitLossChart').getContext('2d');
        profitLossChart = new Chart(profitLossCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Profit/Loss',
                    data: [],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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
                },
                elements: {
                    point: {
                        radius: 4,
                        hoverRadius: 6
                    }
                }
            }
        });

        // Revenue vs Expenses Chart
        const revenueExpensesCtx = document.getElementById('revenueExpensesChart').getContext('2d');
        revenueExpensesChart = new Chart(revenueExpensesCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Revenue',
                        data: [],
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: [],
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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

        // Expense Breakdown Chart
        const expenseBreakdownCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
        expenseBreakdownChart = new Chart(expenseBreakdownCtx, {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        '#3B82F6', '#EF4444', '#10B981', '#F59E0B',
                        '#8B5CF6', '#EC4899', '#6B7280', '#14B8A6'
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

        // Profit Margin Chart
        const profitMarginCtx = document.getElementById('profitMarginChart').getContext('2d');
        profitMarginChart = new Chart(profitMarginCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Profit Margin (%)',
                    data: [],
                    borderColor: 'rgb(168, 85, 247)',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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
    
    function updateSummaryCards(summary) {
        const summaryCards = document.getElementById('summaryCards');
        const profitClass = summary.total_profit >= 0 ? 'text-green-600' : 'text-red-600';
        const profitIcon = summary.total_profit >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6';
        
        summaryCards.innerHTML = `
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
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
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
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
                            <div class="w-8 h-8 ${summary.total_profit >= 0 ? 'bg-green-100' : 'bg-red-100'} rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 ${summary.total_profit >= 0 ? 'text-green-600' : 'text-red-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${profitIcon}"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Net Profit</dt>
                                <dd class="text-lg font-medium ${profitClass}">Rp ${summary.total_profit.toLocaleString('id-ID')}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Profit Margin</dt>
                                <dd class="text-lg font-medium ${profitClass}">${summary.profit_margin.toFixed(1)}%</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    function updateCharts(data, summary) {
        // Update Profit/Loss Chart
        profitLossChart.data.labels = data.map(item => item.label);
        profitLossChart.data.datasets[0].data = data.map(item => item.profit);
        profitLossChart.data.datasets[0].borderColor = data.map(item => 
            item.profit >= 0 ? 'rgb(34, 197, 94)' : 'rgb(239, 68, 68)'
        );
        profitLossChart.data.datasets[0].backgroundColor = data.map(item => 
            item.profit >= 0 ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)'
        );
        profitLossChart.update();
        
        // Update Revenue vs Expenses Chart
        revenueExpensesChart.data.labels = data.map(item => item.label);
        revenueExpensesChart.data.datasets[0].data = data.map(item => item.revenue);
        revenueExpensesChart.data.datasets[1].data = data.map(item => item.expenses);
        revenueExpensesChart.update();
        
        // Update Expense Breakdown Chart
        if (summary.expenses_by_category && summary.expenses_by_category.length > 0) {
            expenseBreakdownChart.data.labels = summary.expenses_by_category.map(item => item.category);
            expenseBreakdownChart.data.datasets[0].data = summary.expenses_by_category.map(item => item.amount);
            expenseBreakdownChart.update();
            
            // Update expense breakdown table
            updateExpenseBreakdownTable(summary.expenses_by_category);
        }
        
        // Update Profit Margin Chart
        profitMarginChart.data.labels = data.map(item => item.label);
        profitMarginChart.data.datasets[0].data = data.map(item => item.profit_margin);
        profitMarginChart.update();
        
        // Update profit margin insights
        updateProfitMarginInsights(summary);
    }
    
    function updateExpenseBreakdownTable(expensesByCategory) {
        const table = document.getElementById('expenseBreakdownTable');
        let tableHTML = `
            <div class="overflow-hidden">
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
        
        expensesByCategory.forEach(item => {
            tableHTML += `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.category}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp ${item.amount.toLocaleString('id-ID')}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.percentage.toFixed(1)}%</td>
                </tr>
            `;
        });
        
        tableHTML += `
                    </tbody>
                </table>
            </div>
        `;
        
        table.innerHTML = tableHTML;
    }
    
    function updateProfitMarginInsights(summary) {
        const insights = document.getElementById('profitMarginInsights');
        const status = summary.status === 'profitable' ? 'Profitable' : 'Loss';
        const statusClass = summary.status === 'profitable' ? 'text-green-600' : 'text-red-600';
        const statusIcon = summary.status === 'profitable' ? '✓' : '⚠';
        
        insights.innerHTML = `
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">Financial Status</h4>
                    <p class="text-lg font-semibold ${statusClass}">${statusIcon} ${status}</p>
                </div>
                <div class="text-right">
                    <h4 class="text-sm font-medium text-gray-900">Average Margin</h4>
                    <p class="text-lg font-semibold ${statusClass}">${summary.profit_margin.toFixed(1)}%</p>
                </div>
            </div>
            <div class="mt-3 text-sm text-gray-600">
                ${summary.status === 'profitable' 
                    ? 'Your business is generating positive returns. Consider reinvesting profits for growth.' 
                    : 'Review expenses and optimize operations to improve profitability.'}
            </div>
        `;
    }
});
</script>
@endsection