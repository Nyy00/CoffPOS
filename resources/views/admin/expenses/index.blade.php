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
                  class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">

                {{-- Input pencarian --}}
                <div class="flex-1">
                    <x-form.input 
                        name="search" 
                        placeholder="Search expenses by description or category..."
                        value="{{ request('search') }}"
                        label="Search Expenses"
                    />
                </div>

                {{-- Filter kategori --}}
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

                {{-- Filter tanggal mulai --}}
                <div class="w-full md:w-40">
                    <x-form.input 
                        name="date_from" 
                        label="From Date"
                        type="date"
                        value="{{ request('date_from') }}"
                    />
                </div>

                {{-- Filter tanggal akhir --}}
                <div class="w-full md:w-40">
                    <x-form.input 
                        name="date_to" 
                        label="To Date"
                        type="date"
                        value="{{ request('date_to') }}"
                    />
                </div>

                {{-- Tombol aksi filter --}}
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
                            <div class="flex items-center space-x-2">
                                {{-- Detail --}}
                                <x-button href="{{ route('admin.expenses.show', $expense) }}" variant="ghost" size="sm" />
                                {{-- Edit --}}
                                <x-button href="{{ route('admin.expenses.edit', $expense) }}" variant="ghost" size="sm" />
                                {{-- Delete --}}
                                <x-button variant="ghost" size="sm"
                                    onclick="confirmDelete('{{ $expense->id }}','{{ $expense->description }}','{{ number_format($expense->amount, 0, ',', '.') }}')"
                                    class="text-red-600" />
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
</script>
@endsection
