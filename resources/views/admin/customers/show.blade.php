@extends('layouts.app')

@section('title', 'Customer Details - ' . $customer->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.customers.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-coffee-dark">{{ $customer->name }}</h1>
                    <p class="text-sm text-gray-500">Customer since {{ $customer->created_at->format('d M Y') }}</p>
                </div>
            </div>
            
            <a href="{{ route('admin.customers.edit', $customer) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Customer
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-coffee-dark mb-4">Personal Information</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Email</p>
                            <p class="text-gray-700 font-medium">{{ $customer->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Phone</p>
                            <p class="text-gray-700 font-medium">{{ $customer->phone }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Date of Birth</p>
                            <p class="text-gray-700 font-medium">{{ $customer->date_of_birth ? $customer->date_of_birth->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Address</p>
                            <p class="text-gray-700 font-medium">{{ $customer->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-coffee-dark mb-4">Transaction History</h3>
                    
                    @if($customer->transactions && $customer->transactions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3">Code</th>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Amount</th>
                                        <th class="px-4 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->transactions as $trx)
                                    <tr class="border-b">
                                        <td class="px-4 py-3 font-medium text-blue-600">
                                            <a href="{{ route('admin.transactions.show', $trx) }}">{{ $trx->transaction_code }}</a>
                                        </td>
                                        <td class="px-4 py-3">{{ $trx->created_at->format('d M Y') }}</td>
                                        <td class="px-4 py-3">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $trx->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($trx->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <p>No transactions yet for this customer</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-coffee-dark mb-4">Customer Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Total Orders</span>
                            <span class="font-bold text-gray-900">{{ $customer->transactions ? $customer->transactions->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Total Spent</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($customer->transactions ? $customer->transactions->sum('total_amount') : 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Average Order</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($customer->transactions ? $customer->transactions->avg('total_amount') : 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    @php
                        // PERBAIKAN: Menggunakan 'points', bukan 'loyalty_points'
                        $points = $customer->points ?? 0;
                        
                        // Logika Level Dinamis
                        $currentLevel = floor($points / 100) + 1;
                        $nextLevelPoints = $currentLevel * 100;
                        
                        $progressPoints = $points % 100;
                        $percentage = ($progressPoints / 100) * 100;
                        
                        if ($points > 0 && $progressPoints == 0) $percentage = 100;
                    @endphp

                    <div class="text-center">
                        <h3 class="text-lg font-bold text-coffee-dark mb-1">Loyalty Status</h3>
                        
                        <div class="text-4xl font-extrabold text-yellow-600 mb-1 mt-4">{{ number_format($points) }}</div>
                        <div class="text-sm text-gray-500 mb-4 font-medium">Current Points</div>
                        
                        <div class="mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                VIP TIER {{ $currentLevel }}
                            </span>
                        </div>
                        
                        <div class="relative w-full h-3 bg-gray-200 rounded-full mb-2 overflow-hidden shadow-inner">
                            <div class="absolute top-0 left-0 h-full bg-yellow-500 rounded-full transition-all duration-1000 ease-out"
                                 style="width: {{ $percentage }}%">
                            </div>
                        </div>
                        
                        <div class="flex justify-between text-xs text-gray-500 font-medium">
                            <span>{{ ($currentLevel - 1) * 100 }}</span>
                            <span>{{ $nextLevelPoints }} (Next Level)</span>
                        </div>

                        @if($percentage < 100)
                            <div class="mt-4 text-xs text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                Need <span class="font-bold text-coffee-dark">{{ 100 - $progressPoints }} more points</span> to reach Tier {{ $currentLevel + 1 }}!
                            </div>
                        @else
                            <div class="mt-4 text-xs text-green-700 bg-green-50 p-3 rounded-lg border border-green-200 font-bold">
                                Congratulations! You've reached Tier {{ $currentLevel }}!
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection