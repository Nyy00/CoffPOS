@extends('layouts.app') 
{{-- Menggunakan layout utama aplikasi --}}

@section('title', 'Products Management') 
{{-- Mengatur judul halaman --}}

@section('content')
<div class="py-6">
    {{-- Wrapper utama dengan padding vertikal --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Container dengan lebar maksimal --}}

        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            {{-- Header halaman --}}
            <div class="flex-1 min-w-0">
                {{-- Judul halaman --}}
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Products Management
                </h2>
                {{-- Deskripsi halaman --}}
                <p class="mt-1 text-sm text-gray-500">
                    Manage your coffee shop products, categories, and inventory
                </p>
            </div>

            {{-- Tombol tambah produk --}}
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <x-button href="{{ route('admin.products.create') }}" variant="primary">
                    {{-- Icon tambah --}}
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    Add Product
                </x-button>
            </div>
        </div>

        <!-- Filters -->
        <x-card class="mb-6">
            {{-- Form filter dan pencarian --}}
            <form method="GET" action="{{ route('admin.products.index') }}"
                class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">

                {{-- Input pencarian --}}
                <div class="flex-1">
                    <x-form.input 
                        name="search" 
                        placeholder="Search products by name, code, or description..."
                        value="{{ request('search') }}"
                        label="Search Products"
                    />
                </div>

                {{-- Filter kategori --}}
                <div class="w-full md:w-48">
                    <x-form.select 
                        name="category_id" 
                        :options="$categories" 
                        value="{{ request('category_id') }}"
                        placeholder="All Categories"
                        label="Category"
                    />
                </div>

                {{-- Filter status produk --}}
                <div class="w-full md:w-32">
                    <x-form.select 
                        name="is_available" 
                        :options="['1' => 'Available', '0' => 'Unavailable']" 
                        value="{{ request('is_available') }}"
                        placeholder="All Status"
                        label="Status"
                    />
                </div>

                {{-- Tombol aksi --}}
                <div class="flex space-x-2">
                    {{-- Tombol search --}}
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z">
                            </path>
                        </svg>
                        Search
                    </x-button>

                    {{-- Tombol clear filter --}}
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
            {{-- Mengecek apakah data produk ada --}}
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

                    {{-- Loop data produk --}}
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        {{-- Kolom gambar --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="h-12 w-12 rounded-lg object-cover">
                            @else
                                {{-- Placeholder jika tidak ada gambar --}}
                                <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16
                                               m-2-2l1.586-1.586a2 2 0 012.828 0L20 14
                                               m-6-6h.01M6 20h12a2 2 0 002-2V6
                                               a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </td>

                        {{-- Nama dan deskripsi --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                            @if($product->description)
                                <div class="text-sm text-gray-500">
                                    {{ Str::limit($product->description, 50) }}
                                </div>
                            @endif
                        </td>

                        {{-- Kode produk --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $product->code }}
                        </td>

                        {{-- Kategori --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <x-badge variant="light">{{ $product->category->name }}</x-badge>
                        </td>

                        {{-- Harga --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>

                        {{-- Stok --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="{{ $product->stock <= $product->min_stock ? 'text-red-600 font-medium' : '' }}">
                                {{ $product->stock }}
                            </span>
                            @if($product->stock <= $product->min_stock)
                                <x-badge variant="danger" size="sm" class="ml-1">Low Stock</x-badge>
                            @endif
                        </td>

                        {{-- Status produk --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->is_available)
                                <x-badge variant="success">Available</x-badge>
                            @else
                                <x-badge variant="danger">Unavailable</x-badge>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                {{-- Detail --}}
                                <x-button href="{{ route('admin.products.show', $product) }}" variant="ghost" size="sm">...</x-button>
                                {{-- Edit --}}
                                <x-button href="{{ route('admin.products.edit', $product) }}" variant="ghost" size="sm">...</x-button>
                                {{-- Hapus --}}
                                <x-button variant="ghost" size="sm"
                                    onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}')"
                                    class="text-red-600 hover:text-red-800">...</x-button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </x-table>

                {{-- Pagination --}}
                <div class="mt-6">
                    <x-pagination :paginator="$products" />
                </div>
            @else
                {{-- Tampilan jika data kosong --}}
                <div class="text-center py-12">...</div>
            @endif
        </x-card>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-modal-enhanced id="deleteModal" title="Delete Product" type="danger">
    {{-- Pesan konfirmasi hapus --}}
    <p class="text-sm text-gray-500">
        Are you sure you want to delete <span id="productName" class="font-medium"></span>? This action cannot be undone.
    </p>

    {{-- Footer modal --}}
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
// Fungsi untuk menampilkan modal konfirmasi hapus
function confirmDelete(productId, productName) {
    document.getElementById('productName').textContent = productName;
    document.getElementById('deleteForm').action = `/admin/products/${productId}`;
    openModal('deleteModal');
}
</script>
@endsection
