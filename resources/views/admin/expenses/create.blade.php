{{-- Menggunakan layout utama aplikasi --}}
@extends('layouts.app')

{{-- Judul halaman --}}
@section('title', 'Add New Expense')

{{-- Konten utama halaman --}}
@section('content')
<div class="py-6">
    {{-- Container utama dengan lebar maksimal --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- ================= HEADER & BREADCRUMB ================= -->
        <div class="mb-6">
            {{-- Breadcrumb navigasi --}}
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">

                    {{-- Tombol kembali ke halaman expenses --}}
                    <li>
                        <a href="{{ route('admin.expenses.index') }}" class="text-gray-400 hover:text-gray-500">
                            {{-- Icon back --}}
                            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Back</span>
                        </a>
                    </li>

                    {{-- Link ke halaman expenses --}}
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('admin.expenses.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Expenses
                            </a>
                        </div>
                    </li>

                    {{-- Posisi halaman saat ini --}}
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

            {{-- Judul halaman --}}
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Add New Expense</h1>
                <p class="mt-1 text-sm text-gray-500">Record a new business expense</p>
            </div>
        </div>

        <!-- ================= VALIDATION & SESSION MESSAGE ================= -->

        {{-- Menampilkan error validasi --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            There were errors with your submission:
                        </h3>
                        <ul class="list-disc list-inside text-sm text-red-700 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Pesan error --}}
        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <!-- ================= FORM INPUT ================= -->

        {{-- Form tambah expense --}}
        <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">

                {{-- Informasi utama expense --}}
                <x-card title="Expense Information">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Deskripsi pengeluaran --}}
                        <x-form.input 
                            name="description" 
                            label="Description"
                            placeholder="e.g., Coffee beans purchase, Equipment repair"
                            required
                        />

                        {{-- Nominal pengeluaran --}}
                        <x-form.input 
                            name="amount" 
                            label="Amount (Rp)"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="50000"
                            required
                        />

                        {{-- Kategori pengeluaran --}}
                        <x-form.select 
                            name="category" 
                            label="Category"
                            :options="$categories"
                            placeholder="Select a category"
                            required
                        />

                        {{-- Tanggal pengeluaran --}}
                        <x-form.input 
                            name="expense_date" 
                            label="Expense Date"
                            type="date"
                            :value="date('Y-m-d')"
                            max="{{ date('Y-m-d') }}"
                            required
                        />
                    </div>

                    {{-- Catatan tambahan --}}
                    <div class="mt-6">
                        <x-form.textarea 
                            name="notes" 
                            label="Additional Notes"
                            placeholder="Any additional details about this expense..."
                            rows="3"
                        />
                    </div>
                </x-card>

                {{-- Upload bukti struk --}}
                <x-card title="Receipt & Documentation">
                    <x-form.file-upload 
                        name="receipt_image" 
                        label="Receipt Image"
                        accept="image/*,application/pdf"
                    />
                </x-card>

                {{-- Tombol aksi --}}
                <div class="flex justify-end space-x-4">
                    <x-button href="{{ route('admin.expenses.index') }}" variant="light">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="primary">
                        Record Expense
                    </x-button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
