@extends('layouts.app')

@section('title', 'Add New Category')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-gray-500">
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
                            <a href="{{ route('admin.categories.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Categories</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">Add New Category</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Add New Category</h1>
                <p class="mt-1 text-sm text-gray-500">Create a new product category for your coffee shop</p>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <x-card title="Category Information">
                    <div class="space-y-6">
                        <x-form.input 
                            name="name" 
                            label="Category Name"
                            placeholder="e.g., Coffee, Tea, Pastries, Snacks"
                            required
                        />
                        
                        <x-form.textarea 
                            name="description" 
                            label="Description"
                            placeholder="Describe this category..."
                            rows="3"
                        />
                    </div>
                </x-card>

                <!-- Category Image -->
                <x-card title="Category Image">
                    <x-form.file-upload 
                        name="image" 
                        label="Category Image"
                        accept="image/*"
                    />
                    <p class="mt-2 text-sm text-gray-500">
                        Upload an image that represents this category. Recommended size: 400x400px or larger.
                    </p>
                </x-card>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <x-button href="{{ route('admin.categories.index') }}" variant="light">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Category
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection