{{-- Menggunakan layout utama aplikasi --}}
@extends('layouts.app')

{{-- Judul halaman edit kategori --}}
@section('title', 'Edit Category - ' . $category->name)

{{-- Konten utama halaman --}}
@section('content')
<div class="py-6">
    {{-- Container utama --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6">

            <!-- Breadcrumb navigasi -->
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">

                    <!-- Tombol kembali ke halaman kategori -->
                    <li>
                        <a href="{{ route('admin.categories.index') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Back</span>
                        </a>
                    </li>

                    <!-- Link ke daftar kategori -->
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('admin.categories.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Categories
                            </a>
                        </div>
                    </li>

                    <!-- Halaman aktif -->
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">
                                Edit {{ $category->name }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Judul dan deskripsi halaman -->
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Edit Category</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Update category information
                </p>
            </div>
        </div>

        <!-- Form edit kategori -->
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf {{-- Token keamanan CSRF --}}
            @method('PUT') {{-- Method HTTP PUT untuk update data --}}

            <div class="space-y-6">

                <!-- Informasi dasar kategori -->
                <x-card title="Category Information">
                    <div class="space-y-6">

                        {{-- Input nama kategori --}}
                        <x-form.input 
                            name="name" 
                            label="Category Name"
                            placeholder="e.g., Coffee, Tea, Pastries, Snacks"
                            :value="$category->name"
                            required
                        />

                        {{-- Input deskripsi kategori --}}
                        <x-form.textarea 
                            name="description" 
                            label="Description"
                            placeholder="Describe this category..."
                            :value="$category->description"
                            rows="3"
                        />
                    </div>
                </x-card>

                <!-- Upload / ganti gambar kategori -->
                <x-card title="Category Image">
                    <x-form.file-upload 
                        name="image" 
                        label="Category Image"
                        accept="image/*"
                        :current-image="$category->image ? Storage::url($category->image) : ''"
                    />
                    <p class="mt-2 text-sm text-gray-500">
                        Upload a new image to replace the current one, or leave empty to keep the existing image.
                    </p>
                </x-card>

                <!-- Statistik kategori -->
                @if($category->products->count() > 0)
                <x-card title="Category Statistics">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Total produk dalam kategori --}}
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $category->products->count() }}
                            </div>
                            <div class="text-sm text-gray-500">Total Products</div>
                        </div>

                        {{-- Jumlah produk yang tersedia --}}
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $category->products->where('is_available', true)->count() }}
                            </div>
                            <div class="text-sm text-gray-500">Available Products</div>
                        </div>

                        {{-- Jumlah produk dengan stok menipis --}}
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $category->products->where('stock', '<=', DB::raw('min_stock'))->count() }}
                            </div>
                            <div class="text-sm text-gray-500">Low Stock Products</div>
                        </div>
                    </div>
                </x-card>
                @endif

                <!-- Tombol aksi -->
                <div class="flex justify-end space-x-4">

                    {{-- Tombol batal --}}
                    <x-button href="{{ route('admin.categories.index') }}" variant="light">
                        Cancel
                    </x-button>

                    {{-- Tombol simpan perubahan --}}
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Category
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
