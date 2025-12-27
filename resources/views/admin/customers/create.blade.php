@extends('layouts.app')

@section('title', 'Add New Customer')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                            <span class="ml-4 text-sm font-medium text-gray-500">Add New Customer</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Add New Customer</h1>
                <p class="mt-1 text-sm text-gray-500">Register a new customer for your loyalty program</p>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Personal Information -->
                <x-card title="Personal Information">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="name" 
                            label="Full Name"
                            placeholder="e.g., John Doe"
                            required
                        />
                        
                        <x-form.input 
                            name="email" 
                            label="Email Address"
                            type="email"
                            placeholder="john@example.com"
                            required
                        />
                        
                        <x-form.input 
                            name="phone" 
                            label="Phone Number"
                            placeholder="e.g., +62 812 3456 7890"
                        />
                        
                        <x-form.input 
                            name="date_of_birth" 
                            label="Date of Birth"
                            type="date"
                        />
                    </div>
                    
                    <div class="mt-6">
                        <x-form.textarea 
                            name="address" 
                            label="Address"
                            placeholder="Customer's address..."
                            rows="3"
                        />
                    </div>
                </x-card>

                <!-- Loyalty Program -->
                <x-card title="Loyalty Program">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="loyalty_points" 
                            label="Initial Loyalty Points"
                            type="number"
                            min="0"
                            value="0"
                            placeholder="0"
                        />
                        
                        <div class="flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-sm text-gray-500">Starting Points</div>
                                <div class="text-2xl font-bold text-blue-600">0</div>
                                <div class="text-xs text-gray-400">Points will be earned from purchases</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Loyalty Program Info</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Customers earn 1 point for every Rp 1,000 spent</li>
                                        <li>Points can be redeemed for discounts on future purchases</li>
                                        <li>Points never expire</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <x-button href="{{ route('admin.customers.index') }}" variant="light">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Customer
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection