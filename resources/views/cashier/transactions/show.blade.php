@extends('layouts.app')

@section('title', 'Transaction Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Transaction Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('cashier.transactions.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Back to Transactions
            </a>
            <form method="POST" action="{{ route('cashier.transactions.reprint', $transaction) }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Reprint Receipt
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <!-- Transaction Header -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Information</h3>
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Transaction Code:</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->transaction_code }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Date & Time:</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Cashier:</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->user->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Payment Method:</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</dd>
                    </div>
                </dl>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                @if($transaction->customer)
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Name:</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->customer->name }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Phone:</dt>
                        <dd class="text-sm text-gray-900">{{ $transaction->customer->phone }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Points Earned:</dt>
                        <dd class="text-sm text-gray-900">{{ floor($transaction->total_amount / 1000) }} points</dd>
                    </div>
                </dl>
                @else
                <p class="text-sm text-gray-500">Walk-in Customer</p>
                @endif
            </div>
        </div>

        <!-- Transaction Items -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Items</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subtotal
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($transaction->transactionItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->product->name }}
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

        <!-- Transaction Summary -->
        <div class="border-t pt-6">
            <div class="max-w-md ml-auto">
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Subtotal:</dt>
                        <dd class="text-sm text-gray-900">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</dd>
                    </div>
                    @if($transaction->discount > 0)
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Discount:</dt>
                        <dd class="text-sm text-red-600">-Rp {{ number_format($transaction->discount, 0, ',', '.') }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Tax:</dt>
                        <dd class="text-sm text-gray-900">Rp {{ number_format($transaction->tax, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between border-t pt-2">
                        <dt class="text-base font-semibold text-gray-900">Total:</dt>
                        <dd class="text-base font-semibold text-gray-900">Rp {{ number_format($transaction->total, 0, ',', '.') }}</dd>
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

        @if($transaction->notes)
        <div class="mt-6 border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Notes</h3>
            <p class="text-sm text-gray-600">{{ $transaction->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection