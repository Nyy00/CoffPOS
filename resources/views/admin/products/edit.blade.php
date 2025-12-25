@extends('layouts.app')

@section('title', 'Edit Product - ' . $product->name)

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.products.index') }}" class="text-gray-400 hover:text-gray-500">
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
                            <a href="{{ route('admin.products.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Products</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">Edit {{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
                <p class="mt-1 text-sm text-gray-500">Update product information</p>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Basic Information -->
                <x-card title="Basic Information">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="name" 
                            label="Product Name"
                            placeholder="e.g., Espresso, Cappuccino"
                            :value="$product->name"
                            required
                        />
                        
                        <x-form.input 
                            name="code" 
                            label="Product Code"
                            placeholder="e.g., ESP001, CAP001"
                            :value="$product->code"
                            required
                        />
                        
                        <x-form.select 
                            name="category_id" 
                            label="Category"
                            :options="$categories"
                            :value="$product->category_id"
                            placeholder="Select a category"
                            required
                        />
                        
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_available" value="1" {{ $product->is_available ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Available for sale</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <x-form.textarea 
                            name="description" 
                            label="Description"
                            placeholder="Describe your product..."
                            :value="$product->description"
                            rows="3"
                        />
                    </div>
                </x-card>

                <!-- Pricing & Inventory -->
                <x-card title="Pricing & Inventory">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-form.input 
                            name="price" 
                            label="Price (Rp)"
                            type="number"
                            step="0.01"
                            min="0"
                            :value="$product->price"
                            placeholder="25000"
                            required
                        />
                        
                        <x-form.input 
                            name="stock" 
                            label="Current Stock"
                            type="number"
                            min="0"
                            :value="$product->stock"
                            placeholder="100"
                            required
                        />
                        
                        <x-form.input 
                            name="min_stock" 
                            label="Minimum Stock Alert"
                            type="number"
                            min="0"
                            :value="$product->min_stock"
                            placeholder="10"
                            required
                        />
                    </div>
                </x-card>

                <!-- Product Image -->
                <x-card title="Product Image">
                    <x-form.file-upload 
                        name="image" 
                        label="Product Image"
                        accept="image/*"
                        :current-image="$product->image ? Storage::url($product->image) : ''"
                    />
                    <p class="mt-2 text-sm text-gray-500">
                        Upload a new image to replace the current one, or leave empty to keep the existing image.
                    </p>
                </x-card>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <x-button href="{{ route('admin.products.index') }}" variant="light">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Product
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection