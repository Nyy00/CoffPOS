@extends('layouts.app')

@section('title', 'Products Management')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Products Management
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your coffee shop products, categories, and inventory
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <x-button href="{{ route('admin.products.create') }}" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Product
                </x-button>
            </div>
        </div>

        <!-- Filters -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('admin.products.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                <div class="flex-1">
                    <x-form.input 
                        name="search" 
                        placeholder="Search products by name, code, or description..."
                        value="{{ request('search') }}"
                        label="Search Products"
                    />
                </div>
                <div class="w-full md:w-48">
                    <x-form.select 
                        name="category_id" 
                        :options="$categories" 
                        value="{{ request('category_id') }}"
                        placeholder="All Categories"
                        label="Category"
                    />
                </div>
                <div class="w-full md:w-32">
                    <x-form.select 
                        name="is_available" 
                        :options="['1' => 'Available', '0' => 'Unavailable']" 
                        value="{{ request('is_available') }}"
                        placeholder="All Status"
                        label="Status"
                    />
                </div>
                <div class="flex space-x-2">
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </x-button>
                    @if(request()->hasAny(['search', 'category_id', 'is_available']))
                        <x-button href="{{ route('admin.products.index') }}" variant="light">
                            Clear
                        </x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Products Table -->
        <x-card>
            @if($products->count() > 0)
                <x-table :headers="[
                    ['label' => 'Image', 'key' => 'image'],
                    ['label' => 'Product', 'key' => 'name', 'sortable' => true],
                    ['label' => 'Code', 'key' => 'code', 'sortable' => true],
                    ['label' => 'Category', 'key' => 'category', 'sortable' => true],
                    ['label' => 'Price', 'key' => 'price', 'sortable' => true],
                    ['label' => 'Stock', 'key' => 'stock', 'sortable' => true],
                    ['label' => 'Status', 'key' => 'status'],
                    ['label' => 'Actions', 'key' => 'actions']
                ]">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded-lg object-cover">
                            @else
                                <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                            @if($product->description)
                                <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $product->code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <x-badge variant="light">{{ $product->category->name }}</x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="{{ $product->stock <= $product->min_stock ? 'text-red-600 font-medium' : '' }}">
                                {{ $product->stock }}
                            </span>
                            @if($product->stock <= $product->min_stock)
                                <x-badge variant="danger" size="sm" class="ml-1">Low Stock</x-badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->is_available)
                                <x-badge variant="success">Available</x-badge>
                            @else
                                <x-badge variant="danger">Unavailable</x-badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <x-button href="{{ route('admin.products.show', $product) }}" variant="ghost" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </x-button>
                                <x-button href="{{ route('admin.products.edit', $product) }}" variant="ghost" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </x-button>
                                <x-button 
                                    variant="ghost" 
                                    size="sm"
                                    onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </x-button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </x-table>

                <!-- Pagination -->
                <div class="mt-6">
                    <x-pagination :paginator="$products" />
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
                    <div class="mt-6">
                        <x-button href="{{ route('admin.products.create') }}" variant="primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Product
                        </x-button>
                    </div>
                </div>
            @endif
        </x-card>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-modal-enhanced id="deleteModal" title="Delete Product" type="danger">
    <p class="text-sm text-gray-500">
        Are you sure you want to delete <span id="productName" class="font-medium"></span>? This action cannot be undone.
    </p>
    
    <x-slot name="footer">
        <form id="deleteForm" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="danger">Delete</x-button>
        </form>
        <x-button type="button" variant="light" onclick="closeModal('deleteModal')" class="ml-3">
            Cancel
        </x-button>
    </x-slot>
</x-modal-enhanced>

<script>
function confirmDelete(productId, productName) {
    document.getElementById('productName').textContent = productName;
    document.getElementById('deleteForm').action = `/admin/products/${productId}`;
    openModal('deleteModal');
}
</script>
@endsection