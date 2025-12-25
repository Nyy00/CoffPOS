@extends('layouts.app')

@section('title', 'Add New Expense')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.expenses.index') }}" class="text-gray-400 hover:text-gray-500">
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
                            <a href="{{ route('admin.expenses.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Expenses</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">Add New Expense</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Add New Expense</h1>
                <p class="mt-1 text-sm text-gray-500">Record a new business expense</p>
            </div>
        </div>

        <!-- Form -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data" onsubmit="console.log('Form submitted'); return true;">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <x-card title="Expense Information">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="description" 
                            label="Description"
                            placeholder="e.g., Coffee beans purchase, Equipment repair"
                            required
                        />
                        
                        <x-form.input 
                            name="amount" 
                            label="Amount (Rp)"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="50000"
                            required
                        />
                        
                        <x-form.select 
                            name="category" 
                            label="Category"
                            :options="$categories"
                            placeholder="Select a category"
                            required
                        />
                        
                        <x-form.input 
                            name="expense_date" 
                            label="Expense Date"
                            type="date"
                            :value="date('Y-m-d')"
                            max="{{ date('Y-m-d') }}"
                            required
                        />
                    </div>
                    
                    <div class="mt-6">
                        <x-form.textarea 
                            name="notes" 
                            label="Additional Notes"
                            placeholder="Any additional details about this expense..."
                            rows="3"
                        />
                    </div>
                </x-card>

                <!-- Receipt Upload -->
                <x-card title="Receipt & Documentation">
                    <x-form.file-upload 
                        name="receipt_image" 
                        label="Receipt Image"
                        accept="image/*,application/pdf"
                    />
                    <p class="mt-2 text-sm text-gray-500">
                        Upload a photo or scan of the receipt. Accepted formats: JPG, PNG, PDF. Maximum size: 10MB.
                    </p>
                    
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Receipt Tips</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Take clear photos with good lighting</li>
                                        <li>Ensure all text is readable</li>
                                        <li>Include the full receipt showing date, amount, and vendor</li>
                                        <li>Receipts help with tax deductions and expense tracking</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Category Guidelines -->
                <x-card title="Category Guidelines">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <h4 class="text-sm font-medium text-blue-800">Inventory & Supplies</h4>
                                <p class="text-sm text-blue-700">{{ $categoryDescriptions['inventory'] }}</p>
                            </div>
                            <div class="p-3 bg-yellow-50 rounded-lg">
                                <h4 class="text-sm font-medium text-yellow-800">Operational Costs</h4>
                                <p class="text-sm text-yellow-700">{{ $categoryDescriptions['operational'] }}</p>
                            </div>
                            <div class="p-3 bg-green-50 rounded-lg">
                                <h4 class="text-sm font-medium text-green-800">Salary & Benefits</h4>
                                <p class="text-sm text-green-700">{{ $categoryDescriptions['salary'] }}</p>
                            </div>
                            <div class="p-3 bg-purple-50 rounded-lg">
                                <h4 class="text-sm font-medium text-purple-800">Utilities</h4>
                                <p class="text-sm text-purple-700">{{ $categoryDescriptions['utilities'] }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="p-3 bg-red-50 rounded-lg">
                                <h4 class="text-sm font-medium text-red-800">Marketing & Advertising</h4>
                                <p class="text-sm text-red-700">{{ $categoryDescriptions['marketing'] }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-800">Maintenance & Repairs</h4>
                                <p class="text-sm text-gray-700">{{ $categoryDescriptions['maintenance'] }}</p>
                            </div>
                            <div class="p-3 bg-indigo-50 rounded-lg">
                                <h4 class="text-sm font-medium text-indigo-800">Other Expenses</h4>
                                <p class="text-sm text-indigo-700">{{ $categoryDescriptions['other'] }}</p>
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <x-button href="{{ route('admin.expenses.index') }}" variant="light">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Record Expense
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection