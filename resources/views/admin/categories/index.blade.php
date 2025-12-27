{{-- Menggunakan layout utama aplikasi --}}
@extends('layouts.app')

{{-- Judul halaman --}}
@section('title', 'Categories Management')

{{-- Konten utama --}}
@section('content')
<div class="py-6">
    {{-- Container halaman --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header halaman -->
        <div class="md:flex md:items-center md:justify-between mb-6">

            <!-- Judul dan deskripsi -->
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Categories Management
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage product categories for your coffee shop
                </p>
            </div>

            <!-- Tombol tambah kategori -->
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <x-button href="{{ route('admin.categories.create') }}" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Category
                </x-button>
            </div>
        </div>

        <!-- Form pencarian kategori -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('admin.categories.index') }}" class="flex items-end space-x-4">

                {{-- Input pencarian --}}
                <div class="flex-1">
                    <x-form.input 
                        name="search" 
                        placeholder="Search categories by name or description..."
                        value="{{ request('search') }}"
                        label="Search Categories"
                    />
                </div>

                {{-- Tombol aksi pencarian --}}
                <div class="flex space-x-2">
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </x-button>

                    {{-- Tombol reset pencarian --}}
                    @if(request('search'))
                        <x-button href="{{ route('admin.categories.index') }}" variant="light">
                            Clear
                        </x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Tabel kategori -->
        <x-card>
            @if($categories->count() > 0)

                {{-- Komponen tabel --}}
                <x-table :headers="[
                    ['label' => 'Image', 'key' => 'image'],
                    ['label' => 'Category', 'key' => 'name', 'sortable' => true],
                    ['label' => 'Description', 'key' => 'description'],
                    ['label' => 'Products Count', 'key' => 'products_count', 'sortable' => true],
                    ['label' => 'Created', 'key' => 'created_at', 'sortable' => true],
                    ['label' => 'Actions', 'key' => 'actions']
                ]">

                    {{-- Loop data kategori --}}
                    @foreach($categories as $category)
                    <tr class="hover:bg-gray-50">

                        <!-- Kolom gambar -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($category->image)
                                <img src="{{ Storage::url($category->image) }}"
                                     alt="{{ $category->name }}"
                                     class="h-12 w-12 rounded-lg object-cover">
                            @else
                                {{-- Placeholder jika tidak ada gambar --}}
                                <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            @endif
                        </td>

                        <!-- Nama kategori -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $category->name }}
                            </div>
                        </td>

                        <!-- Deskripsi kategori -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">
                                {{ $category->description ? Str::limit($category->description, 60) : '-' }}
                            </div>
                        </td>

                        <!-- Jumlah produk -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <x-badge variant="light">
                                {{ $category->products_count }} products
                            </x-badge>
                        </td>

                        <!-- Tanggal dibuat -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $category->created_at->format('d M Y') }}
                        </td>

                        <!-- Tombol aksi -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2">

                                {{-- Tombol edit --}}
                                <x-button href="{{ route('admin.categories.edit', $category) }}"
                                          variant="ghost" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </x-button>

                                {{-- Tombol hapus --}}
                                <x-button 
                                    variant="ghost" 
                                    size="sm"
                                    onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}', {{ $category->products_count }})"
                                    class="text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </x-button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </x-table>

                <!-- Pagination -->
                <div class="mt-6">
                    <x-pagination :paginator="$categories" />
                </div>

            @else
                <!-- Tampilan jika data kosong -->
                <div class="text-center py-12">
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        No categories found
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Get started by creating a new category.
                    </p>
                </div>
            @endif
        </x-card>
    </div>
</div>

<!-- Modal konfirmasi hapus kategori -->
<x-modal-enhanced id="deleteModal" title="Delete Category" type="danger">
    <p class="text-sm text-gray-500">
        Are you sure you want to delete
        <span id="categoryName" class="font-medium"></span>?
    </p>

    {{-- Peringatan jika kategori memiliki produk --}}
    <p id="productsWarning" class="text-sm text-red-600 mt-2 hidden">
        <strong>Warning:</strong>
        This category has <span id="productsCount"></span> products.
    </p>

    <p class="text-sm text-gray-500 mt-2">
        This action cannot be undone.
    </p>

    {{-- Footer modal --}}
    <x-slot name="footer">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="danger">Delete</x-button>
        </form>

        <x-button type="button" variant="light"
                  onclick="closeModal('deleteModal')" class="ml-3">
            Cancel
        </x-button>
    </x-slot>
</x-modal-enhanced>

{{-- Script konfirmasi hapus --}}
<script>
function confirmDelete(categoryId, categoryName, productsCount) {
    document.getElementById('categoryName').textContent = categoryName;
    document.getElementById('deleteForm').action = `/admin/categories/${categoryId}`;

    const productsWarning = document.getElementById('productsWarning');
    const productsCountSpan = document.getElementById('productsCount');

    if (productsCount > 0) {
        productsCountSpan.textContent = productsCount;
        productsWarning.classList.remove('hidden');
    } else {
        productsWarning.classList.add('hidden');
    }

    openModal('deleteModal');
}
</script>
@endsection
