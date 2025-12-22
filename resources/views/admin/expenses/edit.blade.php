@extends('layouts.app')

@section('title', 'Edit Expense - ' . $expense->description)

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
                            <span class="ml-4 text-sm font-medium text-gray-500">Edit {{ Str::limit($expense->description, 30) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Edit Expense</h1>
                <p class="mt-1 text-sm text-gray-500">Update expense information</p>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.expenses.update', $expense) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <x-card title="Expense Information">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="description" 
                            label="Description"
                            placeholder="e.g., Coffee beans purchase, Equipment repair"
                            :value="$expense->description"
                            required
                        />
                        
                        <x-form.input 
                            name="amount" 
                            label="Amount (Rp)"
                            type="number"
                            step="0.01"
                            min="0"
                            :value="$expense->amount"
                            placeholder="50000"
                            required
                        />
                        
                        <x-form.select 
                            name="category" 
                            label="Category"
                            :options="$categories"
                            :value="$expense->category"
                            placeholder="Select a category"
                            required
                        />
                        
                        <x-form.input 
                            name="expense_date" 
                            label="Expense Date"
                            type="date"
                            :value="$expense->expense_date->format('Y-m-d')"
                            required
                        />
                    </div>
                    
                    <div class="mt-6">
                        <x-form.textarea 
                            name="notes" 
                            label="Additional Notes"
                            placeholder="Any additional details about this expense..."
                            :value="$expense->notes"
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
                        :current-image="$expense->receipt_image ? Storage::url($expense->receipt_image) : ''"
                    />
                    <p class="mt-2 text-sm text-gray-500">
                        Upload a new receipt to replace the current one, or leave empty to keep the existing receipt.
                    </p>
                    
                    @if($expense->receipt_image)
                        <div class="mt-4 p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-green-800">Current Receipt Available</h3>
                                    <div class="mt-1 text-sm text-green-700">
                                        <a href="{{ Storage::url($expense->receipt_image) }}" target="_blank" class="underline hover:no-underline">
                                            View current receipt
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </x-card>

                <!-- Expense History -->
                <x-card title="Expense Details">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">{{ $expense->user->name }}</div>
                            <div class="text-sm text-gray-500">Added By</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">{{ $expense->created_at->format('d M Y, H:i') }}</div>
                            <div class="text-sm text-gray-500">Created At</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">{{ $expense->updated_at->diffForHumans() }}</div>
                            <div class="text-sm text-gray-500">Last Updated</div>
                        </div>
                    </div>
                    
                    @if($expense->created_at != $expense->updated_at)
                        <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
                            <div class="flex">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Expense Modified</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        This expense has been modified since it was created. Changes will be tracked for audit purposes.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
                        Update Expense
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection