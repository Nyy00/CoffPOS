@extends('layouts.app')

@section('title', 'Product Details - ' . $product->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                            <span class="ml-4 text-sm font-medium text-gray-500">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <p class="mt-1 text-sm text-gray-500">Product Code: {{ $product->code }}</p>
                </div>
                <div class="flex space-x-3">
                    <x-button href="{{ route('admin.products.edit', $product) }}" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Product
                    </x-button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <x-card title="Product Information">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Category</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                <x-badge variant="light">{{ $product->category->name }}</x-badge>
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Status</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($product->is_available)
                                    <x-badge variant="success">Available</x-badge>
                                @else
                                    <x-badge variant="danger">Unavailable</x-badge>
                                @endif
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Price</h4>
                            <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Current Stock</h4>
                            <p class="mt-1 text-lg font-semibold {{ $product->stock <= $product->min_stock ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $product->stock }} units
                                @if($product->stock <= $product->min_stock)
                                    <x-badge variant="danger" size="sm" class="ml-2">Low Stock</x-badge>
                                @endif
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Minimum Stock Alert</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->min_stock }} units</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Created At</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($product->description)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500">Description</h4>
                        <p class="mt-2 text-sm text-gray-900">{{ $product->description }}</p>
                    </div>
                    @endif
                </x-card>

                <!-- Transaction History -->
                <x-card title="Recent Transactions">
                    @if($product->transactionItems->count() > 0)
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($product->transactionItems->take(10) as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                            <a href="{{ route('admin.transactions.show', $item->transaction) }}">
                                                {{ $item->transaction->transaction_code }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->transaction->created_at->format('d M Y, H:i') }}
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
                    @else
                        <div class="text-center py-6">
                            <p class="text-sm text-gray-500">No transactions yet for this product</p>
                        </div>
                    @endif
                </x-card>
            </div>

            <!-- Product Image -->
            <div class="lg:col-span-1">
                <x-card title="Product Image">
                    @if($product->image)
                        <img src="{{ asset('images/products/' . str_replace('products/', '', $product->image)) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-auto rounded-lg"
                             onerror="this.onerror=null; this.src='{{ Storage::url($product->image) }}';">
                    @else
                        <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 text-center">No image available</p>
                    @endif
                </x-card>

                <!-- Quick Stats -->
                <x-card title="Statistics" class="mt-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Sold</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                {{ $product->transactionItems->sum('quantity') }} units
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Revenue</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                Rp {{ number_format($product->transactionItems->sum('subtotal'), 0, ',', '.') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Transactions</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">
                                {{ $product->transactionItems->count() }}
                            </dd>
                        </div>
                    </dl>
                </x-card>
            </div>
        </div>
    </div>
</div>
@endsection