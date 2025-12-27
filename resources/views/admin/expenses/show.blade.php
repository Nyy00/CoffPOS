{{-- Menggunakan layout utama --}}
@extends('layouts.app')

{{-- Judul halaman --}}
@section('title', 'Expense Details - ' . $expense->description)

{{-- Konten utama --}}
@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- ================= HEADER & BREADCRUMB ================= -->
        <div class="mb-6">
            <!-- Breadcrumb navigation -->
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">

                    <!-- Tombol kembali -->
                    <li>
                        <a href="{{ route('admin.expenses.index') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Back</span>
                        </a>
                    </li>

                    <!-- Link ke halaman expenses -->
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

                    <!-- Judul expense -->
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">
                                {{ Str::limit($expense->description, 30) }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Judul halaman & tombol edit -->
            <div class="mt-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $expense->description }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Expense recorded on {{ $expense->expense_date->format('d M Y') }}
                    </p>
                </div>

                <div class="flex space-x-3">
                    <x-button href="{{ route('admin.expenses.edit', $expense) }}" variant="primary">
                        Edit Expense
                    </x-button>
                </div>
            </div>
        </div>

        <!-- ================= GRID LAYOUT ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ================= DETAIL EXPENSE ================= -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Informasi utama expense -->
                <x-card title="Expense Information">
                    <div class="grid grid-cols-2 gap-6">

                        <!-- Nominal -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Amount</h4>
                            <p class="mt-1 text-2xl font-bold text-gray-900">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Category</h4>
                            <p class="mt-1">
                                {{-- Mapping warna kategori --}}
                                @php
                                    $categoryColors = [
                                        'supplies' => 'info',
                                        'equipment' => 'warning',
                                        'utilities' => 'primary',
                                        'rent' => 'danger',
                                        'marketing' => 'success',
                                        'maintenance' => 'secondary',
                                        'other' => 'light'
                                    ];
                                @endphp

                                <x-badge variant="{{ $categoryColors[$expense->category] ?? 'light' }}" size="lg">
                                    {{ ucfirst($expense->category) }}
                                </x-badge>
                            </p>
                        </div>

                        <!-- Tanggal expense -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Expense Date</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $expense->expense_date->format('d M Y') }}
                            </p>
                        </div>

                        <!-- User -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Added By</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $expense->user->name }}
                            </p>
                        </div>

                        <!-- Created at -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Created At</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $expense->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        <!-- Updated at -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Last Updated</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $expense->updated_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Catatan tambahan -->
                    @if($expense->notes)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-500">Additional Notes</h4>
                            <p class="mt-2 text-sm text-gray-900">
                                {{ $expense->notes }}
                            </p>
                        </div>
                    @endif
                </x-card>

                <!-- Informasi kategori (deskripsi & contoh) -->
                <x-card title="Category Information">
                    {{-- Data statis kategori --}}
                    @php
                        $categoryInfo = [
                            'supplies' => [
                                'description' => 'Essential supplies for daily operations including coffee beans, milk, sugar, cups, napkins, and food ingredients.',
                                'examples' => ['Coffee beans', 'Milk and dairy products', 'Sugar and sweeteners', 'Disposable cups', 'Food ingredients'],
                                'color' => 'blue'
                            ],
                            'equipment' => [
                                'description' => 'Equipment purchases and upgrades for the coffee shop operations.',
                                'examples' => ['Coffee machines', 'Grinders', 'POS systems', 'Furniture', 'Kitchen equipment'],
                                'color' => 'yellow'
                            ],
                            'utilities' => [
                                'description' => 'Monthly utility bills and services required for business operations.',
                                'examples' => ['Electricity bills', 'Water bills', 'Gas bills', 'Internet service', 'Phone service'],
                                'color' => 'blue'
                            ],
                            'rent' => [
                                'description' => 'Property-related expenses including rent, taxes, and insurance.',
                                'examples' => ['Monthly rent', 'Property taxes', 'Business insurance', 'Security deposits'],
                                'color' => 'red'
                            ],
                            'marketing' => [
                                'description' => 'Marketing and promotional activities to attract customers.',
                                'examples' => ['Social media ads', 'Flyers and posters', 'Promotional events', 'Website maintenance'],
                                'color' => 'green'
                            ],
                            'maintenance' => [
                                'description' => 'Maintenance and repair services to keep equipment and facilities running.',
                                'examples' => ['Equipment repairs', 'Cleaning supplies', 'Maintenance services', 'Replacement parts'],
                                'color' => 'gray'
                            ],
                            'other' => [
                                'description' => 'Other business expenses that don\'t fit into specific categories.',
                                'examples' => ['Office supplies', 'Professional services', 'Training costs', 'Miscellaneous expenses'],
                                'color' => 'gray'
                            ]
                        ];

                        $info = $categoryInfo[$expense->category] ?? $categoryInfo['other'];
                    @endphp

                    <div class="p-4 bg-{{ $info['color'] }}-50 rounded-lg">
                        <h4 class="text-sm font-medium text-{{ $info['color'] }}-800 mb-2">
                            {{ ucfirst($expense->category) }} Category
                        </h4>

                        <p class="text-sm text-{{ $info['color'] }}-700 mb-3">
                            {{ $info['description'] }}
                        </p>

                        <div class="text-sm text-{{ $info['color'] }}-700">
                            <strong>Common examples:</strong>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                @foreach($info['examples'] as $example)
                                    <li>{{ $example }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL HAPUS ================= --}}
<x-modal-enhanced id="deleteModal" title="Delete Expense" type="danger">
    <p class="text-sm text-gray-500">
        Are you sure you want to delete the expense
        "<span id="expenseDescription" class="font-medium"></span>"
        with amount Rp <span id="expenseAmount" class="font-medium"></span>?
    </p>

    <x-slot name="footer">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="danger">Delete</x-button>
        </form>
        <x-button type="button" variant="light" onclick="closeModal('deleteModal')">
            Cancel
        </x-button>
    </x-slot>
</x-modal-enhanced>

{{-- Script konfirmasi hapus --}}
<script>
function confirmDelete(expenseId, expenseDescription, expenseAmount) {
    document.getElementById('expenseDescription').textContent = expenseDescription;
    document.getElementById('expenseAmount').textContent = expenseAmount;
    document.getElementById('deleteForm').action = `/admin/expenses/${expenseId}`;
    openModal('deleteModal');
}
</script>
@endsection
