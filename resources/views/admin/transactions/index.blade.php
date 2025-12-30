@extends('layouts.app')
{{-- Menggunakan layout utama aplikasi --}}

@section('title', 'Transactions')
{{-- Judul halaman transaksi --}}

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Container utama halaman --}}

    {{-- ================= ALERT MESSAGES ================= --}}
    @if(session('success'))
        <x-alert type="success" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error" class="mb-6">
            {{ session('error') }}
        </x-alert>
    @endif

    <div class="flex justify-between items-center mb-6">
        {{-- Header halaman --}}
        <h1 class="text-3xl font-bold text-gray-900">Transactions</h1>

        {{-- Tombol aksi (export) --}}
        <div class="flex space-x-3">
            <a href="{{ route('admin.transactions.export', request()->all()) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                Export CSV
            </a>
        </div>
    </div>

    <!-- Filters -->
    {{-- Card filter transaksi --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Filter pencarian --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Transaction code or customer name"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            {{-- Filter status transaksi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Filter metode pembayaran --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="payment_method" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Methods</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method }}" {{ request('payment_method') == $method ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $method)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Filter kasir --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cashier</label>
                <select name="cashier_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Cashiers</option>
                    @foreach($cashiers as $cashier)
                        <option value="{{ $cashier->id }}" {{ request('cashier_id') == $cashier->id ? 'selected' : '' }}>
                            {{ $cashier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Filter tanggal awal --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            {{-- Filter tanggal akhir --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            {{-- Filter minimum total transaksi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Min Amount</label>
                <input type="number" name="amount_min" value="{{ request('amount_min') }}" 
                       placeholder="0"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            {{-- Filter maksimum total transaksi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max Amount</label>
                <input type="number" name="amount_max" value="{{ request('amount_max') }}" 
                       placeholder="999999999"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            {{-- Tombol aksi filter --}}
            <div class="md:col-span-2 lg:col-span-4 flex items-end justify-end space-x-2">
                <x-button type="submit" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search
                </x-button>
                @if(request()->hasAny(['search', 'status', 'payment_method', 'cashier_id', 'date_from', 'date_to', 'amount_min', 'amount_max']))
                    <x-button href="{{ route('admin.transactions.index') }}" variant="light">
                        Clear
                    </x-button>
                @endif
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    {{-- Tabel data transaksi --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                {{-- Header tabel --}}
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Transaction</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Cashier</th>
                        <th class="px-6 py-3">Items</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Payment</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Actions</th>
                    </tr>
                </thead>

                {{-- Body tabel --}}
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- Loop data transaksi --}}
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        {{-- Kode & waktu transaksi --}}
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $transaction->transaction_code }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $transaction->created_at->format('M j, Y H:i') }}
                                </div>
                            </div>
                        </td>

                        {{-- Nama customer --}}
                        <td class="px-6 py-4">
                            {{ $transaction->customer ? $transaction->customer->name : 'Walk-in' }}
                        </td>

                        {{-- Nama kasir --}}
                        <td class="px-6 py-4">
                            {{ $transaction->user->name }}
                        </td>

                        {{-- Total item --}}
                        <td class="px-6 py-4">
                            {{ $transaction->transactionItems->sum('quantity') }}
                        </td>

                        {{-- Total pembayaran --}}
                        <td class="px-6 py-4 font-medium">
                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                        </td>

                        {{-- Metode pembayaran --}}
                        <td class="px-6 py-4">
                            {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                        </td>

                        {{-- Status transaksi --}}
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($transaction->status === 'voided' ? 'bg-red-100 text-red-800' : 
                                    'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.transactions.show', $transaction) }}">View</a>
                                @if($transaction->status === 'completed' && $transaction->created_at->diffInHours(now()) <= 24)
                                <button onclick="openVoidModal({{ $transaction->id }}, '{{ $transaction->transaction_code }}')">
                                    Void
                                </button>
                                @endif
                                <form method="POST" action="{{ route('admin.transactions.reprint-receipt', $transaction) }}" target="_blank">
                                    @csrf
                                    <button type="submit">Receipt</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    {{-- Jika tidak ada data --}}
                    <tr>
                        <td colspan="8" class="text-center text-gray-500">
                            No transactions found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $transactions->appends(request()->all())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modal void transaksi --}}
<x-modal-enhanced id="voidModal" title="Void Transaction" type="danger">
    <p class="text-sm text-gray-500">
        Are you sure you want to void transaction <span id="voidTransactionCode" class="font-medium"></span>?
    </p>
    <p class="text-sm text-red-600 mt-2">
        This action cannot be undone. The transaction will be marked as voided and cannot be processed further.
    </p>
    
    <div class="mt-4">
        <label for="voidReason" class="block text-sm font-medium text-gray-700 mb-2">
            Reason for voiding <span class="text-red-500">*</span>
        </label>
        <textarea id="voidReason" name="reason" rows="3" required
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                  placeholder="Please provide a reason for voiding this transaction..."></textarea>
    </div>
    
    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
        <p class="text-sm text-yellow-800">
            <strong>Note:</strong> Voiding a transaction will:
        </p>
        <ul class="text-sm text-yellow-800 mt-1 ml-4 list-disc">
            <li>Mark the transaction as voided</li>
            <li>Restore product stock quantities</li>
            <li>Remove any loyalty points earned</li>
        </ul>
    </div>

    <x-slot name="footer">
        <form id="voidForm" method="POST" class="inline">
            @csrf
            <input type="hidden" name="reason" id="voidReasonInput">
            <x-button type="submit" variant="danger" onclick="submitVoidForm()">Void Transaction</x-button>
        </form>
        <x-button type="button" variant="light" onclick="closeVoidModal()" class="ml-3">
            Cancel
        </x-button>
    </x-slot>
</x-modal-enhanced>

{{-- Script JavaScript --}}
<script>
    // Fungsi buka modal void
    function openVoidModal(transactionId, transactionCode) {
        document.getElementById('voidTransactionCode').textContent = transactionCode;
        document.getElementById('voidForm').action = `/admin/transactions/${transactionId}/void`;
        document.getElementById('voidReason').value = '';
        document.getElementById('voidReasonInput').value = '';
        openModal('voidModal');
    }

    // Fungsi tutup modal void
    function closeVoidModal() {
        closeModal('voidModal');
        document.getElementById('voidForm').reset();
        document.getElementById('voidReason').value = '';
    }
    
    // Fungsi submit form void
    function submitVoidForm() {
        const reason = document.getElementById('voidReason').value.trim();
        if (!reason) {
            alert('Please provide a reason for voiding this transaction.');
            return false;
        }
        document.getElementById('voidReasonInput').value = reason;
        return true;
    }
</script>

@endsection
