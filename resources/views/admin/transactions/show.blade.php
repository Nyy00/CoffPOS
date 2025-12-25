@extends('layouts.app')

@section('title', 'Transaction Details - ' . $transaction->transaction_code)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.transactions.index') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Back</span>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('admin.transactions.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Transaksi
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">{{ $transaction->transaction_code }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Transaction Details -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Transaction Details
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Complete information about transaction {{ $transaction->transaction_code }}
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Transaction Code</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $transaction->transaction_code }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $transaction->created_at->format('d M Y, H:i:s') }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Cashier</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $transaction->user->name }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Customer</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if($transaction->customer)
                                <a href="{{ route('admin.customers.show', $transaction->customer) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $transaction->customer->name }}
                                </a>
                                <span class="text-gray-500"> - {{ $transaction->customer->phone }}</span>
                            @else
                                <span class="text-gray-500">Walk-in Customer</span>
                            @endif
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                            </span>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Transaction Items -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Item</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Produk yang dibeli dalam transaksi ini</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transaction->transactionItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($item->product && $item->product->image)
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}">
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                @if($item->product)
                                                    <a href="{{ route('admin.products.show', $item->product) }}" class="text-blue-600 hover:text-blue-900">
                                                        {{ $item->product->name }}
                                                    </a>
                                                @else
                                                    {{ $item->product_name }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->product->category->name ?? 'No Category' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Transaction Summary -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Transaction Summary</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <div class="max-w-md ml-auto">
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Subtotal:</dt>
                            <dd class="text-sm text-gray-900">Rp {{ number_format($transaction->subtotal ?? $transaction->subtotal_amount ?? 0, 0, ',', '.') }}</dd>
                        </div>
                        @if(($transaction->discount ?? $transaction->discount_amount ?? 0) > 0)
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Discount:</dt>
                            <dd class="text-sm text-red-600">-Rp {{ number_format($transaction->discount ?? $transaction->discount_amount ?? 0, 0, ',', '.') }}</dd>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Tax:</dt>
                            <dd class="text-sm text-gray-900">Rp {{ number_format($transaction->tax ?? $transaction->tax_amount ?? 0, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between border-t pt-2">
                            <dt class="text-base font-semibold text-gray-900">Total:</dt>
                            <dd class="text-base font-semibold text-gray-900">Rp {{ number_format($transaction->total ?? $transaction->total_amount ?? 0, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Payment Amount:</dt>
                            <dd class="text-sm text-gray-900">Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Change:</dt>
                            <dd class="text-sm text-gray-900">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        @if($transaction->notes)
        <!-- Notes -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Notes</h3>
                <p class="mt-1 text-sm text-gray-600">{{ $transaction->notes }}</p>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="mt-8 flex justify-end space-x-3">
            <form method="POST" action="{{ route('admin.transactions.reprint-receipt', $transaction) }}" target="_blank" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Print Receipt
                </button>
            </form>
            
            @if($transaction->status === 'completed')
            <button type="button" onclick="voidTransaction()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Void Transaction
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Void Transaction Modal -->
<div id="void-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">Void Transaction</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to void this transaction? This action cannot be undone.
                </p>
                <form id="void-form" method="POST" action="{{ route('admin.transactions.void', $transaction) }}" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason for voiding:</label>
                        <textarea id="reason" name="reason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                    </div>
                </form>
            </div>
            <div class="items-center px-4 py-3">
                <button type="submit" form="void-form" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700">
                    Void
                </button>
                <button onclick="closeVoidModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function voidTransaction() {
    document.getElementById('void-modal').classList.remove('hidden');
}

function closeVoidModal() {
    document.getElementById('void-modal').classList.add('hidden');
}
</script>
@endsection