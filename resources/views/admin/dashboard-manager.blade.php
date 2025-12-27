@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900">Manager Dashboard</h1>
                    <p class="mt-1 text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}! Here's your operational overview.</p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <select id="periodSelector" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="today" selected>Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6" id="statsCards">
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
                            <dt class="text-sm font-medium text-gray-500 truncate" id="revenueLabel">Today's Revenue</dt>
                            <dd class="text-lg font-medium text-gray-900" id="revenueValue">Rp {{ number_format($stats['today']['revenue'], 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Today's Expenses -->
            <x-card>
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
                            <dt class="text-sm font-medium text-gray-500 truncate" id="expensesLabel">Today's Expenses</dt>
                            <dd class="text-lg font-medium text-gray-900" id="expensesValue">Rp {{ number_format($stats['today']['expenses'], 0, ',', '.') }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Today's Profit -->
            <x-card>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 {{ $stats['today']['profit'] >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center" id="profitIcon">
                            <svg class="w-5 h-5 {{ $stats['today']['profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate" id="profitLabel">Today's Profit</dt>
                            <dd class="text-lg font-medium {{ $stats['today']['profit'] >= 0 ? 'text-gray-900' : 'text-red-600' }}" id="profitValue">
                                Rp {{ number_format($stats['today']['profit'], 0, ',', '.') }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Pending Expenses -->
            <x-card>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Approvals</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['pending_expenses'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Expense Management -->
                <x-card title="Recent Expenses">
                    @if($recentExpenses->count() > 0)
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentExpenses as $expense)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                            <a href="{{ route('admin.expenses.show', $expense) }}">
                                                {{ Str::limit($expense->description, 30) }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($expense->category) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $expense->expense_date->format('d M Y') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-center">
                            <x-button href="{{ route('admin.expenses.index') }}" variant="light">
                                View All Expenses
                            </x-button>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-sm text-gray-500">No expenses recorded today</p>
                            <div class="mt-4">
                                <x-button href="{{ route('admin.expenses.create') }}" variant="primary">
                                    Record First Expense
                                </x-button>
                            </div>
                        </div>
                    @endif
                </x-card>

                <!-- Expense Trend Chart -->
                <x-card title="Expense Trend (Last 7 Days)">
                    <div class="h-64">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </x-card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Expense Categories -->
                <x-card title="Expense Categories (This Month)">
                    <div class="space-y-3" id="expenseCategories">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </x-card>

                <!-- Budget Alerts -->
                <x-card title="Budget Alerts" class="border-l-4 border-yellow-500">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">Monthly Budget</p>
                                <p class="text-xs text-gray-500">{{ date('F Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">75% Used</p>
                                <p class="text-xs text-yellow-600">Rp 7.5M / 10M</p>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                </x-card>

                <!-- Quick Actions -->
                <x-card title="Quick Actions">
                    <div class="space-y-3">
                        <x-button href="{{ route('admin.expenses.create') }}" variant="primary" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Record Expense
                        </x-button>
                        <x-button href="{{ route('admin.expenses.index') }}" variant="light" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View All Expenses
                        </x-button>
                        <x-button href="{{ route('admin.expenses.export') }}" variant="light" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Report
                        </x-button>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize expense chart
    initializeExpenseChart();
    
    // Load expense categories
    loadExpenseCategories();
    
    // Load initial dashboard data
    loadDashboardData();
    
    // Period selector event listener
    document.getElementById('periodSelector').addEventListener('change', function() {
        loadDashboardData();
    });
});

function loadDashboardData() {
    const period = document.getElementById('periodSelector').value;
    
    fetch(`/admin/expenses/dashboard/api?period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateStatsCards(data.data, period);
                updateExpenseCategories(data.data.expenses_by_category);
            }
        })
        .catch(error => {
            console.error('Error loading dashboard data:', error);
        });
}

function updateStatsCards(data, period) {
    // Update labels based on period
    const labels = {
        'today': { revenue: "Today's Revenue", expenses: "Today's Expenses", profit: "Today's Profit" },
        'week': { revenue: "This Week's Revenue", expenses: "This Week's Expenses", profit: "This Week's Profit" },
        'month': { revenue: "This Month's Revenue", expenses: "This Month's Expenses", profit: "This Month's Profit" },
        'year': { revenue: "This Year's Revenue", expenses: "This Year's Expenses", profit: "This Year's Profit" }
    };
    
    const currentLabels = labels[period] || labels['today'];
    
    // Update labels
    document.getElementById('revenueLabel').textContent = currentLabels.revenue;
    document.getElementById('expensesLabel').textContent = currentLabels.expenses;
    document.getElementById('profitLabel').textContent = currentLabels.profit;
    
    // Update values
    document.getElementById('revenueValue').textContent = 'Rp ' + data.revenue.toLocaleString('id-ID');
    document.getElementById('expensesValue').textContent = 'Rp ' + data.expenses.toLocaleString('id-ID');
    
    const profit = data.revenue - data.expenses;
    document.getElementById('profitValue').textContent = 'Rp ' + profit.toLocaleString('id-ID');
    
    // Update profit styling
    const profitIcon = document.getElementById('profitIcon');
    const profitValue = document.getElementById('profitValue');
    
    if (profit >= 0) {
        profitIcon.className = 'w-8 h-8 bg-green-100 rounded-full flex items-center justify-center';
        profitIcon.querySelector('svg').className = 'w-5 h-5 text-green-600';
        profitValue.className = 'text-lg font-medium text-gray-900';
    } else {
        profitIcon.className = 'w-8 h-8 bg-red-100 rounded-full flex items-center justify-center';
        profitIcon.querySelector('svg').className = 'w-5 h-5 text-red-600';
        profitValue.className = 'text-lg font-medium text-red-600';
    }
}

function initializeExpenseChart() {
    const ctx = document.getElementById('expenseChart');
    if (!ctx || typeof Chart === 'undefined') {
        console.error('Chart canvas or Chart.js not found');
        return;
    }

    // Sample data - in real implementation, this would come from API
    const expenseData = {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
            label: 'Daily Expenses',
            data: [120000, 190000, 300000, 500000, 200000, 300000, 450000],
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4,
            fill: true
        }]
    };

    new Chart(ctx, {
        type: 'line',
        data: expenseData,
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
            }
        }
    });
}

function loadExpenseCategories() {
    // Load real data from API
    fetch('/admin/expenses/dashboard/api')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateExpenseCategories(data.data.expenses_by_category);
            }
        })
        .catch(error => {
            console.error('Error loading expense categories:', error);
            // Fallback to sample data
            loadSampleCategories();
        });
}

function updateExpenseCategories(categories) {
    const container = document.getElementById('expenseCategories');
    let html = '';

    if (categories.length === 0) {
        html = '<p class="text-sm text-gray-500">No expenses recorded this month</p>';
    } else {
        const total = categories.reduce((sum, cat) => sum + cat.amount, 0);
        
        categories.forEach(category => {
            const percentage = total > 0 ? ((category.amount / total) * 100).toFixed(1) : 0;
            const color = getColorForCategory(category.category);
            html += `
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full ${color} mr-3"></div>
                        <span class="text-sm font-medium text-gray-900">${category.category}</span>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">Rp ${category.amount.toLocaleString('id-ID')}</p>
                        <p class="text-xs text-gray-500">${percentage}%</p>
                    </div>
                </div>
            `;
        });
    }

    container.innerHTML = html;
}

function loadSampleCategories() {
    // Fallback sample data
    const categories = [
        { category: 'Inventory', amount: 2500000 },
        { category: 'Operational', amount: 1500000 },
        { category: 'Salary', amount: 1000000 },
        { category: 'Utilities', amount: 500000 }
    ];
    updateExpenseCategories(categories);
}

function getColorForCategory(category) {
    const colors = {
        'Inventory': 'bg-blue-500',
        'Operational': 'bg-yellow-500',
        'Salary': 'bg-green-500',
        'Utilities': 'bg-purple-500'
    };
    return colors[category] || 'bg-gray-500';
}
</script>
@endsection