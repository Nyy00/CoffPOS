{{-- Menggunakan layout utama aplikasi --}}
@extends('layouts.app')

{{-- Title halaman manajemen expenses --}}
@section('title', 'Expenses Management')

{{-- Section konten utama --}}
@section('content')
<div class="py-6">
    {{-- Container utama halaman --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ================= HEADER ================= --}}
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                {{-- Judul halaman --}}
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Expenses Management
                </h2>
                {{-- Deskripsi singkat --}}
                <p class="mt-1 text-sm text-gray-500">
                    Track and manage business expenses
                </p>
            </div>

            {{-- Tombol tambah expense --}}
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <x-button href="{{ route('admin.expenses.create') }}" variant="primary">
                    {{-- Icon tambah --}}
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Expense
                </x-button>
            </div>
        </div>

        {{-- ================= FILTER ================= --}}
        <x-card class="mb-6">
            {{-- Form filter dengan metode GET --}}
            <form method="GET" action="{{ route('admin.expenses.index') }}"
                  class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                {{-- Input pencarian --}}
                <div class="lg:col-span-2">
                    <x-form.input 
                        name="search" 
                        placeholder="Search expenses by description or category..."
                        value="{{ request('search') }}"
                        label="Search Expenses"
                    />
                </div>

                {{-- Filter kategori --}}
                <div>
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

                {{-- Filter tanggal mulai --}}
                <div>
                    <x-form.input 
                        name="date_from" 
                        label="From Date"
                        type="date"
                        value="{{ request('date_from') }}"
                    />
                </div>

                {{-- Filter tanggal akhir --}}
                <div>
                    <x-form.input 
                        name="date_to" 
                        label="To Date"
                        type="date"
                        value="{{ request('date_to') }}"
                    />
                </div>

                {{-- Tombol aksi filter --}}
                <div class="lg:col-span-5 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">&nbsp;</label>
                    <div class="flex space-x-2">
                        <x-button type="submit" variant="primary">
                            {{-- Icon search --}}
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </x-button>

                        {{-- Tombol reset filter --}}
                        @if(request()->hasAny(['search', 'category', 'date_from', 'date_to']))
                            <x-button href="{{ route('admin.expenses.index') }}" variant="light">
                                Clear
                            </x-button>
                        @endif
                    </div>
                </div>
            </form>
        </x-card>

        {{-- ================= SUMMARY ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            {{-- Total expenses --}}
            <x-card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">
                        Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500">Total Expenses</div>
                </div>
            </x-card>

            {{-- Expense bulan ini --}}
            <x-card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">
                        Rp {{ number_format($monthlyExpenses, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500">This Month</div>
                </div>
            </x-card>

            {{-- Jumlah data --}}
            <x-card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $expensesCount }}</div>
                    <div class="text-sm text-gray-500">Total Records</div>
                </div>
            </x-card>

            {{-- Rata-rata expense --}}
            <x-card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">
                        Rp {{ number_format($averageExpense, 0, ',', '.') }}
                    </div>
                    <div class="text-sm text-gray-500">Average Amount</div>
                </div>
            </x-card>
        </div>

        {{-- ================= CATEGORY COLOR LEGEND ================= --}}
        <x-card class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Category Color Guide</h3>
                <p class="text-sm text-gray-500">Visual reference for expense categories</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                @php
                    $categoryLegend = [
                        'inventory' => ['color' => 'purple', 'label' => 'Inventory', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        'operational' => ['color' => 'orange', 'label' => 'Operational', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                        'salary' => ['color' => 'emerald', 'label' => 'Salary', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                        'utilities' => ['color' => 'cyan', 'label' => 'Utilities', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                        'marketing' => ['color' => 'pink', 'label' => 'Marketing', 'icon' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z'],
                        'maintenance' => ['color' => 'amber', 'label' => 'Maintenance', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                        'other' => ['color' => 'indigo', 'label' => 'Other', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z']
                    ];
                @endphp
                
                @foreach($categoryLegend as $key => $legend)
                    <div class="flex items-center space-x-2 p-2 bg-{{ $legend['color'] }}-50 rounded-lg border border-{{ $legend['color'] }}-200 cursor-help" 
                         title="Click to filter by {{ $legend['label'] }} category"
                         onclick="filterByCategory('{{ $key }}')">
                        <div class="w-6 h-6 bg-{{ $legend['color'] }}-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-{{ $legend['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $legend['icon'] }}"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-medium text-{{ $legend['color'] }}-800">{{ $legend['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </x-card>

        {{-- ================= PROFIT / LOSS QUICK VIEW ================= --}}
        <div class="mb-6">
            <x-card>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Financial Overview</h3>
                        <p class="text-sm text-gray-500">Quick profit/loss analysis for this month</p>
                    </div>

                    {{-- Link laporan detail --}}
                    <div class="text-right">
                        <a href="{{ route('admin.reports.profit-loss') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md
                                  text-indigo-600 bg-indigo-100 hover:bg-indigo-200">
                            {{-- Icon grafik --}}
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            View Detailed Analysis
                        </a>
                    </div>
                </div>

                {{-- Container profit/loss diisi via JavaScript --}}
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4" id="profitLossQuickView">
                    <!-- Data akan dimuat melalui JavaScript -->
                </div>
            </x-card>
        </div>

        {{-- ================= TABEL EXPENSES ================= --}}
        <x-card>
            @if($expenses->count() > 0)

                {{-- Komponen tabel --}}
                <x-table :headers="[
                    ['label' => 'Date', 'key' => 'expense_date', 'sortable' => true],
                    ['label' => 'Description', 'key' => 'description', 'sortable' => true],
                    ['label' => 'Category', 'key' => 'category', 'sortable' => true],
                    ['label' => 'Amount', 'key' => 'amount', 'sortable' => true],
                    ['label' => 'Receipt', 'key' => 'receipt'],
                    ['label' => 'Added By', 'key' => 'user'],
                    ['label' => 'Actions', 'key' => 'actions']
                ]">

                    {{-- Loop data expenses --}}
                    @foreach($expenses as $expense)
                    <tr class="hover:bg-gray-50">

                        {{-- Tanggal expense --}}
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $expense->expense_date->format('d M Y') }}
                        </td>

                        {{-- Deskripsi & catatan --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $expense->description }}</div>
                            @if($expense->notes)
                                <div class="text-sm text-gray-500">
                                    {{ Str::limit($expense->notes, 50) }}
                                </div>
                            @endif
                        </td>

                        {{-- Kategori --}}
                        <td class="px-6 py-4">
                            @php
                                $categoryColors = [
                                    'inventory' => 'purple',    
                                    'operational' => 'orange',   
                                    'salary' => 'emerald',        
                                    'utilities' => 'cyan',       
                                    'marketing' => 'pink',        
                                    'maintenance' => 'amber',     
                                    'other' => 'indigo'          
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

                        {{-- Nominal --}}
                        <td class="px-6 py-4 font-medium text-gray-900">
                            Rp {{ number_format($expense->amount, 0, ',', '.') }}
                        </td>

                        {{-- Status receipt --}}
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($expense->receipt_image)
                                <span class="text-green-600">Available</span>
                            @else
                                <span class="text-gray-500">No receipt</span>
                            @endif
                        </td>

                        {{-- User --}}
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $expense->user->name }}
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                {{-- Detail --}}
                                <x-button href="{{ route('admin.expenses.show', $expense) }}" variant="ghost" size="sm" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </x-button>
                                
                                {{-- Edit --}}
                                <x-button href="{{ route('admin.expenses.edit', $expense) }}" variant="ghost" size="sm" title="Edit Expense">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </x-button>
                                
                                {{-- Delete --}}
                                <x-button variant="ghost" size="sm" title="Delete Expense"
                                    onclick="confirmDelete('{{ $expense->id }}','{{ $expense->description }}','{{ number_format($expense->amount, 0, ',', '.') }}')"
                                    class="text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </x-button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </x-table>

                {{-- Pagination --}}
                <div class="mt-6">
                    <x-pagination :paginator="$expenses" />
                </div>

            @else
                {{-- Tampilan kosong --}}
                <div class="text-center py-12">
                    <p class="text-gray-500">No expenses found</p>
                </div>
            @endif
        </x-card>
    </div>
</div>

{{-- ================= MODAL DELETE ================= --}}
<x-modal-enhanced id="deleteModal" title="Delete Expense" type="danger">
    <p class="text-sm text-gray-500">
        Konfirmasi penghapusan expense
    </p>

    <x-slot name="footer">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="danger">Delete</x-button>
        </form>
        <x-button variant="light" onclick="closeModal('deleteModal')">Cancel</x-button>
    </x-slot>
</x-modal-enhanced>

{{-- ================= SCRIPT ================= --}}
<script>
    // Menampilkan modal delete
    function confirmDelete(id, desc, amount) {
        document.getElementById('deleteForm').action = `/admin/expenses/${id}`;
        openModal('deleteModal');
    }

    // Load data profit/loss saat halaman dibuka
    document.addEventListener('DOMContentLoaded', function () {
        loadProfitLossQuickView();
    });

    // Function untuk load profit/loss quick view
    function loadProfitLossQuickView() {
        const container = document.getElementById('profitLossQuickView');
        if (!container) return;

        // Show loading state
        container.innerHTML = `
            <div class="col-span-3 text-center py-4">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600 mx-auto"></div>
                <p class="mt-2 text-sm text-gray-500">Loading financial overview...</p>
            </div>
        `;

        // Fetch data from chart-data API with 30days period
        const apiUrl = '{{ route("admin.expenses.chart-data.api") }}?period=30days';
        console.log('Fetching from URL:', apiUrl);
        
        fetch(apiUrl)
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.success && data.data) {
                    const revenue = data.data.revenue || 0;
                    const expenses = data.data.expenses || 0;
                    const profit = revenue - expenses;
                    const profitMargin = revenue > 0 ? ((profit / revenue) * 100) : 0;

                    container.innerHTML = `
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-lg font-semibold text-blue-900">Monthly Revenue</div>
                            <div class="text-xl font-bold text-blue-600">Rp ${revenue.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-lg font-semibold text-red-900">Monthly Expenses</div>
                            <div class="text-xl font-bold text-red-600">Rp ${expenses.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="text-center p-4 ${profit >= 0 ? 'bg-green-50' : 'bg-red-50'} rounded-lg">
                            <div class="text-lg font-semibold ${profit >= 0 ? 'text-green-900' : 'text-red-900'}">Net Profit (${profitMargin.toFixed(1)}%)</div>
                            <div class="text-xl font-bold ${profit >= 0 ? 'text-green-600' : 'text-red-600'}">Rp ${profit.toLocaleString('id-ID')}</div>
                        </div>
                    `;
                } else {
                    throw new Error('Invalid response format');
                }
            })
            .catch(error => {
                console.error('Error loading profit/loss data:', error);
                
                // Fallback: tampilkan data dari server-side jika API gagal
                const fallbackRevenue = {{ $monthlyRevenue ?? 0 }};
                const fallbackExpenses = {{ $monthlyExpenses ?? 0 }};
                const fallbackProfit = fallbackRevenue - fallbackExpenses;
                const fallbackMargin = fallbackRevenue > 0 ? ((fallbackProfit / fallbackRevenue) * 100) : 0;
                
                container.innerHTML = `
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-lg font-semibold text-blue-900">Monthly Revenue</div>
                        <div class="text-xl font-bold text-blue-600">Rp ${fallbackRevenue.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-lg font-semibold text-red-900">Monthly Expenses</div>
                        <div class="text-xl font-bold text-red-600">Rp ${fallbackExpenses.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="text-center p-4 ${fallbackProfit >= 0 ? 'bg-green-50' : 'bg-red-50'} rounded-lg">
                        <div class="text-lg font-semibold ${fallbackProfit >= 0 ? 'text-green-900' : 'text-red-900'}">Net Profit (${fallbackMargin.toFixed(1)}%)</div>
                        <div class="text-xl font-bold ${fallbackProfit >= 0 ? 'text-green-600' : 'text-red-600'}">Rp ${fallbackProfit.toLocaleString('id-ID')}</div>
                    </div>
                    <div class="col-span-3 text-center mt-2">
                        <p class="text-xs text-gray-500">Using cached data - <button onclick="loadProfitLossQuickView()" class="text-indigo-600 hover:text-indigo-800">Retry API</button></p>
                    </div>
                `;
            });
    }

    // Function untuk filter berdasarkan kategori dari legend
    function filterByCategory(category) {
        const categorySelect = document.querySelector('select[name="category"]');
        if (categorySelect) {
            categorySelect.value = category;
            // Submit form filter
            categorySelect.closest('form').submit();
        }
    }
</script>
@endsection
