@extends('layouts.app')

@section('title', 'Customer Details - ' . $customer->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.customers.index') }}" class="text-gray-400 hover:text-gray-500">
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
                            <a href="{{ route('admin.customers.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Customers</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">{{ $customer->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16">
                        <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-xl font-medium text-blue-600">
                                {{ strtoupper(substr($customer->name, 0, 2)) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-6">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
                        <p class="mt-1 text-sm text-gray-500">Customer since {{ $customer->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <x-button href="{{ route('admin.customers.edit', $customer) }}" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Customer
                    </x-button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Customer Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Details -->
                <x-card title="Personal Information">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Email</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->email }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Phone</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $customer->phone ?: '-' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Date of Birth</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $customer->date_of_birth ? $customer->date_of_birth->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Loyalty Points</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="font-medium">{{ $customer->loyalty_points }} points</span>
                                </div>
                            </p>
                        </div>
                    </div>
                    
                    @if($customer->address)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500">Address</h4>
                        <p class="mt-2 text-sm text-gray-900">{{ $customer->address }}</p>
                    </div>
                    @endif
                </x-card>

                <!-- Transaction History -->
                <x-card title="Transaction History">
                    @if($customer->transactions->count() > 0)
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points Earned</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($customer->transactions->take(10) as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                            <a href="{{ route('admin.transactions.show', $transaction) }}">
                                                {{ $transaction->transaction_code }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaction->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $transaction->transactionItems->count() }} items
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <svg class="w-3 h-3 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                {{ floor($transaction->total_amount / 1000) }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($customer->transactions->count() > 10)
                        <div class="mt-4 text-center">
                            <x-button href="{{ route('admin.transactions.index', ['customer_id' => $customer->id]) }}" variant="light">
                                View All Transactions
                            </x-button>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <p class="text-sm text-gray-500">No transactions yet for this customer</p>
                        </div>
                    @endif
                </x-card>
            </div>

            <!-- Statistics Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Stats -->
                <x-card title="Customer Statistics">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Orders</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                {{ $customer->transactions->count() }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Spent</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                Rp {{ number_format($customer->transactions->sum('total_amount'), 0, ',', '.') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Average Order</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                Rp {{ number_format($customer->transactions->avg('total_amount') ?: 0, 0, ',', '.') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Order</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $customer->transactions->first() ? $customer->transactions->first()->created_at->diffForHumans() : 'Never' }}
                            </dd>
                        </div>
                    </dl>
                </x-card>

                <!-- Loyalty Status -->
                <x-card title="Loyalty Status">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $customer->loyalty_points }}</div>
                        <div class="text-sm text-gray-500 mb-4">Current Points</div>
                        
                        @php
                            $nextTier = 100; // Example: next tier at 100 points
                            $progress = min(($customer->loyalty_points / $nextTier) * 100, 100);
                        @endphp
                        
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                        
                        @if($customer->loyalty_points >= $nextTier)
                            <x-badge variant="success">VIP Member</x-badge>
                        @else
                            <div class="text-xs text-gray-500">
                                {{ $nextTier - $customer->loyalty_points }} points to VIP status
                            </div>
                        @endif
                    </div>
                </x-card>

                <!-- Recent Activity -->
                <x-card title="Recent Activity">
                    @if($customer->transactions->count() > 0)
                        <div class="space-y-3">
                            @foreach($customer->transactions->take(5) as $transaction)
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M8 11v6h8v-6M8 11H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2h-2"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        Order {{ $transaction->transaction_code }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ $transaction->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="text-sm text-gray-900">
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">No recent activity</p>
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>
</div>
@endsection