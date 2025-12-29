@extends('layouts.app')

@section('title', 'Edit Customer - ' . $customer->name)

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                            <span class="ml-4 text-sm font-medium text-gray-500">Edit {{ $customer->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Edit Customer</h1>
                <p class="mt-1 text-sm text-gray-500">Update customer information</p>
            </div>
        </div>

        <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <x-card title="Personal Information">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="name" 
                            label="Full Name"
                            placeholder="e.g., John Doe"
                            :value="$customer->name"
                            required
                        />
                        
                        <x-form.input 
                            name="email" 
                            label="Email Address"
                            type="email"
                            placeholder="john@example.com"
                            :value="$customer->email"
                            required
                        />
                        
                        <x-form.input 
                            name="phone" 
                            label="Phone Number"
                            placeholder="e.g., +62 812 3456 7890"
                            :value="$customer->phone"
                        />
                        
                        <x-form.input 
                            name="date_of_birth" 
                            label="Date of Birth"
                            type="date"
                            :value="$customer->date_of_birth ? $customer->date_of_birth->format('Y-m-d') : ''"
                        />
                    </div>
                    
                    <div class="mt-6">
                        <x-form.textarea 
                            name="address" 
                            label="Address"
                            placeholder="Customer's address..."
                            :value="$customer->address"
                            rows="3"
                        />
                    </div>
                </x-card>

                <x-card title="Loyalty Program">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="points" 
                            label="Loyalty Points"
                            type="number"
                            min="0"
                            :value="$customer->points"
                            placeholder="0"
                        />
                        
                        <div class="flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-sm text-gray-500">Current Points</div>
                                <div class="text-2xl font-bold text-blue-600">{{ $customer->points }}</div>
                                <div class="text-xs text-gray-400">Adjust points as needed</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
                        <div class="flex">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Points Adjustment</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    Be careful when adjusting loyalty points. Changes will be reflected immediately and may affect customer rewards.
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>

                @if($customer->transactions->count() > 0)
                <x-card title="Customer Statistics">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $customer->transactions->count() }}</div>
                            <div class="text-sm text-gray-500">Total Orders</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($customer->transactions->sum('total_amount'), 0, ',', '.') }}</div>
                            <div class="text-sm text-gray-500">Total Spent</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($customer->transactions->avg('total_amount'), 0, ',', '.') }}</div>
                            <div class="text-sm text-gray-500">Average Order</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $customer->transactions->where('created_at', '>=', now()->subDays(30))->count() }}</div>
                            <div class="text-sm text-gray-500">Orders (30 days)</div>
                        </div>
                    </div>
                </x-card>
                @endif

                <div class="flex justify-end space-x-4">
                    <x-button href="{{ route('admin.customers.index') }}" variant="light">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Customer
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection