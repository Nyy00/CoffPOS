@extends('layouts.app')

@section('title', 'Expenses Management')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Expenses Management
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Track and manage business expenses
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <x-button href="{{ route('admin.expenses.create') }}" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Expense
                </x-button>
            </div>
        </div>

        <!-- Filters -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('admin.expenses.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                <div class="flex-1">
                    <x-form.input 
                        name="search" 
                        placeholder="Search expenses by description or category..."
                        value="{{ request('search') }}"
                        label="Search Expenses"
                    />
                </div>
                <div class="w-full md:w-48">
                    <x-form.select 
                        name="category" 
                        :options="[
                            'inventory' => 'Inventory & Supplies',
                            'operational' => 'Operational Costs',
                            'salary' => 'Salary & Benefits',
                            'utilities' => 'Utilities',
                            'marketing' => 'Marketing & Advertising',
                            'maintenance' => 'Maintenance & Repairs',
                            'other' => 'Other Expenses'
                        ]" 
                        value="{{ request('category') }}"
                        placeholder="All Categories"
                        label="Category"
                    />
                </div>
                <div class="w-full md:w-40">
                    <x-form.input 
                        name="date_from" 
                        label="From Date"
                        type="date"
                        value="{{ request('date_from') }}"
                    />
                </div>
                <div class="w-full md:w-40">
                    <x-form.input 
                        name="date_to" 
                        label="To Date"
                        type="date"
                        value="{{ request('date_to') }}"
                    />
                </div>
                <div class="flex space-x-2">
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </x-button>
                    @if(request()->hasAny(['search', 'category', 'date_from', 'date_to']))
                        <x-button href="{{ route('admin.expenses.index') }}" variant="light">
                            Clear
                        </x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-500">Total Expenses</div>
                </div>
            </x-card>
            <x-card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($monthlyExpenses, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-500">This Month</div>
                </div>
            </x-card>
            <x-card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $expensesCount }}</div>
                    <div class="text-sm text-gray-500">Total Records</div>
                </div>
            </x-card>
            <x-card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($averageExpense, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-500">Average Amount</div>
                </div>
            </x-card>
        </div>

        <!-- Profit/Loss Quick View -->
        <div class="mb-6">
            <x-card>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Financial Overview</h3>
                        <p class="text-sm text-gray-500">Quick profit/loss analysis for this month</p>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('admin.reports.profit-loss') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-100 hover:bg-indigo-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            View Detailed Analysis
                        </a>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4" id="profitLossQuickView">
                    <!-- Will be populated by JavaScript -->
                </div>
            </x-card>
        </div>

        <!-- Expenses Table -->
        <x-card>
            @if($expenses->count() > 0)
                <x-table :headers="[
                    ['label' => 'Date', 'key' => 'expense_date', 'sortable' => true],
                    ['label' => 'Description', 'key' => 'description', 'sortable' => true],
                    ['label' => 'Category', 'key' => 'category', 'sortable' => true],
                    ['label' => 'Amount', 'key' => 'amount', 'sortable' => true],
                    ['label' => 'Receipt', 'key' => 'receipt'],
                    ['label' => 'Added By', 'key' => 'user'],
                    ['label' => 'Actions', 'key' => 'actions']
                ]">
                    @foreach($expenses as $expense)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $expense->expense_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $expense->description }}</div>
                            @if($expense->notes)
                                <div class="text-sm text-gray-500">{{ Str::limit($expense->notes, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $categoryColors = [
                                    'inventory' => 'info',
                                    'operational' => 'warning',
                                    'salary' => 'success',
                                    'utilities' => 'primary',
                                    'marketing' => 'purple',
                                    'maintenance' => 'secondary',
                                    'other' => 'light'
                                ];
                                $categoryLabels = [
                                    'inventory' => 'Inventory',
                                    'operational' => 'Operational',
                                    'salary' => 'Salary',
                                    'utilities' => 'Utilities',
                                    'marketing' => 'Marketing',
                                    'maintenance' => 'Maintenance',
                                    'other' => 'Other'
                                ];
                            @endphp
                            <x-badge variant="{{ $categoryColors[$expense->category] ?? 'light' }}">
                                {{ $categoryLabels[$expense->category] ?? ucfirst($expense->category) }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($expense->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($expense->receipt_image)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-green-600">Available</span>
                                </div>
                            @else
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span class="text-gray-500">No receipt</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $expense->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <x-button href="{{ route('admin.expenses.show', $expense) }}" variant="ghost" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </x-button>
                                <x-button href="{{ route('admin.expenses.edit', $expense) }}" variant="ghost" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </x-button>
                                <x-button 
                                    variant="ghost" 
                                    size="sm"
                                    onclick="confirmDelete('{{ $expense->id }}', '{{ $expense->description }}', '{{ number_format($expense->amount, 0, ',', '.') }}')"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </x-button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </x-table>

                <!-- Pagination -->
                <div class="mt-6">
                    <x-pagination :paginator="$expenses" />
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No expenses found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by recording your first expense.</p>
                    <div class="mt-6">
                        <x-button href="{{ route('admin.expenses.create') }}" variant="primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Expense
                        </x-button>
                    </div>
                </div>
            @endif
        </x-card>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-modal-enhanced id="deleteModal" title="Delete Expense" type="danger">
    <p class="text-sm text-gray-500">
        Are you sure you want to delete the expense "<span id="expenseDescription" class="font-medium"></span>" 
        with amount Rp <span id="expenseAmount" class="font-medium"></span>?
    </p>
    <p class="text-sm text-gray-500 mt-2">This action cannot be undone and will affect your expense reports.</p>
    
    <x-slot name="footer">
        <form id="deleteForm" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="danger">Delete</x-button>
        </form>
        <x-button type="button" variant="light" onclick="closeModal('deleteModal')" class="ml-3">
            Cancel
        </x-button>
    </x-slot>
</x-modal-enhanced>

<script>
function confirmDelete(expenseId, expenseDescription, expenseAmount) {
    document.getElementById('expenseDescription').textContent = expenseDescription;
    document.getElementById('expenseAmount').textContent = expenseAmount;
    document.getElementById('deleteForm').action = `/admin/expenses/${expenseId}`;
    openModal('deleteModal');
}

// Load profit/loss quick view
document.addEventListener('DOMContentLoaded', function() {
    loadProfitLossQuickView();
});

function loadProfitLossQuickView() {
    fetch('/admin/expenses/profit-loss/api?period=30days')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateProfitLossQuickView(data.summary);
            }
        })
        .catch(error => {
            console.error('Error loading profit/loss data:', error);
        });
}

function updateProfitLossQuickView(summary) {
    const container = document.getElementById('profitLossQuickView');
    const profitClass = summary.total_profit >= 0 ? 'text-green-600' : 'text-red-600';
    const profitBg = summary.total_profit >= 0 ? 'bg-green-50' : 'bg-red-50';
    
    container.innerHTML = `
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            <div class="text-lg font-semibold text-blue-900">Rp ${summary.total_revenue.toLocaleString('id-ID')}</div>
            <div class="text-sm text-blue-600">Monthly Revenue</div>
        </div>
        <div class="text-center p-4 bg-red-50 rounded-lg">
            <div class="text-lg font-semibold text-red-900">Rp ${summary.total_expenses.toLocaleString('id-ID')}</div>
            <div class="text-sm text-red-600">Monthly Expenses</div>
        </div>
        <div class="text-center p-4 ${profitBg} rounded-lg">
            <div class="text-lg font-semibold ${profitClass}">Rp ${summary.total_profit.toLocaleString('id-ID')}</div>
            <div class="text-sm ${profitClass}">Net Profit (${summary.profit_margin.toFixed(1)}%)</div>
        </div>
    `;
}
</script>
@endsection