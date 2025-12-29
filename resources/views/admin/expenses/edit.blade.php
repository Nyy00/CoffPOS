{{-- Menggunakan layout utama aplikasi --}}
@extends('layouts.app')

{{-- Mengatur title halaman dengan deskripsi expense yang sedang diedit --}}
@section('title', 'Edit Expense - ' . $expense->description)

{{-- Section utama konten halaman --}}
@section('content')
<div class="py-6">
    {{-- Container utama dengan lebar maksimal --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ================= HEADER & BREADCRUMB ================= --}}
        <div class="mb-6">
            {{-- Navigasi breadcrumb --}}
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">

                    {{-- Tombol kembali --}}
                    <li>
                        <a href="{{ route('admin.expenses.index') }}" class="text-gray-400 hover:text-gray-500">
                            {{-- Icon panah kembali --}}
                            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            {{-- Aksesibilitas --}}
                            <span class="sr-only">Back</span>
                        </a>
                    </li>

                    {{-- Breadcrumb menuju halaman expenses --}}
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

                    {{-- Breadcrumb halaman aktif --}}
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            {{-- Menampilkan deskripsi expense (dipotong 30 karakter) --}}
                            <span class="ml-4 text-sm font-medium text-gray-500">
                                Edit {{ Str::limit($expense->description, 30) }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Judul halaman --}}
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Edit Expense</h1>
                <p class="mt-1 text-sm text-gray-500">Update expense information</p>
            </div>
        </div>

        {{-- ================= FORM EDIT EXPENSE ================= --}}
        
        {{-- Informasi kategori lengkap --}}
        <x-card title="Category Information Guide" class="mb-6">
            <p class="text-sm text-gray-600 mb-4">
                Choose the appropriate category for your expense. Each category has a specific color code and purpose to help organize your business expenses effectively.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $categoryGuide = [
                        'inventory' => [
                            'color' => 'purple',
                            'bg' => 'bg-purple-50',
                            'border' => 'border-purple-200',
                            'text' => 'text-purple-800',
                            'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                            'title' => 'Inventory & Supplies',
                            'description' => 'Coffee beans, cups, napkins, food ingredients, packaging materials',
                            'examples' => ['Coffee beans', 'Disposable cups', 'Food ingredients', 'Packaging']
                        ],
                        'operational' => [
                            'color' => 'orange',
                            'bg' => 'bg-orange-50',
                            'border' => 'border-orange-200',
                            'text' => 'text-orange-800',
                            'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                            'title' => 'Operational Costs',
                            'description' => 'Rent, insurance, licenses, permits, professional services',
                            'examples' => ['Monthly rent', 'Business insurance', 'Licenses', 'Legal fees']
                        ],
                        'salary' => [
                            'color' => 'emerald',
                            'bg' => 'bg-emerald-50',
                            'border' => 'border-emerald-200',
                            'text' => 'text-emerald-800',
                            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                            'title' => 'Salary & Benefits',
                            'description' => 'Staff wages, benefits, bonuses, training costs',
                            'examples' => ['Monthly salaries', 'Employee benefits', 'Bonuses', 'Training']
                        ],
                        'utilities' => [
                            'color' => 'cyan',
                            'bg' => 'bg-cyan-50',
                            'border' => 'border-cyan-200',
                            'text' => 'text-cyan-800',
                            'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
                            'title' => 'Utilities',
                            'description' => 'Electricity, water, gas, internet, phone bills',
                            'examples' => ['Electricity bills', 'Water bills', 'Internet', 'Phone service']
                        ],
                        'marketing' => [
                            'color' => 'pink',
                            'bg' => 'bg-pink-50',
                            'border' => 'border-pink-200',
                            'text' => 'text-pink-800',
                            'icon' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z',
                            'title' => 'Marketing & Advertising',
                            'description' => 'Advertising, promotions, social media, website costs',
                            'examples' => ['Social media ads', 'Promotional materials', 'Website', 'Events']
                        ],
                        'maintenance' => [
                            'color' => 'amber',
                            'bg' => 'bg-amber-50',
                            'border' => 'border-amber-200',
                            'text' => 'text-amber-800',
                            'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                            'title' => 'Maintenance & Repairs',
                            'description' => 'Equipment repairs, cleaning supplies, facility maintenance',
                            'examples' => ['Equipment repairs', 'Cleaning supplies', 'Maintenance', 'Parts']
                        ],
                        'other' => [
                            'color' => 'indigo',
                            'bg' => 'bg-indigo-50',
                            'border' => 'border-indigo-200',
                            'text' => 'text-indigo-800',
                            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                            'title' => 'Other Expenses',
                            'description' => 'Miscellaneous expenses that don\'t fit other categories',
                            'examples' => ['Office supplies', 'Miscellaneous', 'One-time costs', 'Others']
                        ]
                    ];
                @endphp
                
                @foreach($categoryGuide as $key => $guide)
                    <div class="category-guide-item p-4 rounded-lg {{ $guide['bg'] }} {{ $guide['border'] }} border cursor-pointer hover:shadow-md transition-all duration-200 {{ $expense->category === $key ? 'ring-2 ring-offset-2 ring-' . $guide['color'] . '-500' : '' }}" 
                         onclick="selectCategory('{{ $key }}')" 
                         data-category="{{ $key }}">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 {{ $guide['bg'] }} rounded-full flex items-center justify-center border {{ $guide['border'] }}">
                                    <svg class="w-5 h-5 {{ $guide['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $guide['icon'] }}"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <h4 class="text-sm font-semibold {{ $guide['text'] }}">{{ $guide['title'] }}</h4>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-{{ $guide['color'] }}-100 text-{{ $guide['color'] }}-700">
                                        {{ ucfirst($guide['color']) }}
                                    </span>
                                    @if($expense->category === $key)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">
                                            Current
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs {{ $guide['text'] }} opacity-80 mb-2">
                                    {{ $guide['description'] }}
                                </p>
                                <div class="text-xs {{ $guide['text'] }} opacity-70">
                                    <strong>Examples:</strong> {{ implode(', ', $guide['examples']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start space-x-2">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <strong>Tip:</strong> Click on any category above to change the current selection. The current category is highlighted with a "Current" badge.
                    </div>
                </div>
            </div>
        </x-card>

        <form action="{{ route('admin.expenses.update', $expense) }}" method="POST" enctype="multipart/form-data">
            {{-- CSRF protection --}}
            @csrf
            {{-- Method spoofing untuk PUT --}}
            @method('PUT')
            
            <div class="space-y-6">

                {{-- ===== INFORMASI UTAMA EXPENSE ===== --}}
                <x-card title="Expense Information">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Input deskripsi expense --}}
                        <x-form.input 
                            name="description" 
                            label="Description"
                            placeholder="e.g., Coffee beans purchase, Equipment repair"
                            :value="$expense->description"
                            required
                        />

                        {{-- Input jumlah expense --}}
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

                        {{-- Select kategori expense dengan informasi warna --}}
                        <div>
                            <x-form.select 
                                name="category" 
                                label="Category"
                                :options="$categories"
                                :value="$expense->category"
                                placeholder="Select a category"
                                required
                                id="category-select"
                            />
                            
                            {{-- Informasi kategori dengan warna --}}
                            <div class="mt-3 space-y-2" id="category-info">
                                @php
                                    $categoryInfo = [
                                        'inventory' => [
                                            'color' => 'purple',
                                            'bg' => 'bg-purple-50',
                                            'border' => 'border-purple-200',
                                            'text' => 'text-purple-800',
                                            'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'
                                        ],
                                        'operational' => [
                                            'color' => 'orange',
                                            'bg' => 'bg-orange-50',
                                            'border' => 'border-orange-200',
                                            'text' => 'text-orange-800',
                                            'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
                                        ],
                                        'salary' => [
                                            'color' => 'emerald',
                                            'bg' => 'bg-emerald-50',
                                            'border' => 'border-emerald-200',
                                            'text' => 'text-emerald-800',
                                            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
                                        ],
                                        'utilities' => [
                                            'color' => 'cyan',
                                            'bg' => 'bg-cyan-50',
                                            'border' => 'border-cyan-200',
                                            'text' => 'text-cyan-800',
                                            'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'
                                        ],
                                        'marketing' => [
                                            'color' => 'pink',
                                            'bg' => 'bg-pink-50',
                                            'border' => 'border-pink-200',
                                            'text' => 'text-pink-800',
                                            'icon' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z'
                                        ],
                                        'maintenance' => [
                                            'color' => 'amber',
                                            'bg' => 'bg-amber-50',
                                            'border' => 'border-amber-200',
                                            'text' => 'text-amber-800',
                                            'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
                                        ],
                                        'other' => [
                                            'color' => 'indigo',
                                            'bg' => 'bg-indigo-50',
                                            'border' => 'border-indigo-200',
                                            'text' => 'text-indigo-800',
                                            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                                        ]
                                    ];
                                @endphp
                                
                                @foreach($categoryInfo as $key => $info)
                                    <div class="category-info-item hidden p-3 rounded-lg {{ $info['bg'] }} {{ $info['border'] }} border" data-category="{{ $key }}">
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 {{ $info['bg'] }} rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 {{ $info['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    <h4 class="text-sm font-medium {{ $info['text'] }}">{{ $categories[$key] }}</h4>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $info['color'] }}-100 text-{{ $info['color'] }}-800">
                                                        {{ ucfirst($info['color']) }}
                                                    </span>
                                                </div>
                                                <p class="text-xs {{ $info['text'] }} mt-1 opacity-75">
                                                    {{ $categoryDescriptions[$key] ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Input tanggal expense --}}
                        <x-form.input 
                            name="expense_date" 
                            label="Expense Date"
                            type="date"
                            :value="$expense->expense_date->format('Y-m-d')"
                            required
                        />
                    </div>

                    {{-- Catatan tambahan --}}
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

                {{-- ===== UPLOAD BUKTI STRUK ===== --}}
                <x-card title="Receipt & Documentation">
                    <x-form.file-upload 
                        name="receipt_image" 
                        label="Receipt Image"
                        accept="image/*,application/pdf"
                        :current-image="$expense->receipt_image ? Storage::url($expense->receipt_image) : ''"
                    />

                    {{-- Keterangan upload --}}
                    <p class="mt-2 text-sm text-gray-500">
                        Upload a new receipt to replace the current one, or leave empty to keep the existing receipt.
                    </p>

                    {{-- Menampilkan receipt lama jika ada --}}
                    @if($expense->receipt_image)
                        <div class="mt-4 p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
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

                {{-- ===== DETAIL & RIWAYAT EXPENSE ===== --}}
                <x-card title="Expense Details">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- User yang menambahkan expense --}}
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">{{ $expense->user->name }}</div>
                            <div class="text-sm text-gray-500">Added By</div>
                        </div>

                        {{-- Tanggal pembuatan --}}
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $expense->created_at->format('d M Y, H:i') }}
                            </div>
                            <div class="text-sm text-gray-500">Created At</div>
                        </div>

                        {{-- Terakhir diperbarui --}}
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $expense->updated_at->diffForHumans() }}
                            </div>
                            <div class="text-sm text-gray-500">Last Updated</div>
                        </div>
                    </div>

                    {{-- Peringatan jika data pernah diubah --}}
                    @if($expense->created_at != $expense->updated_at)
                        <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
                            <div class="flex">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                          clip-rule="evenodd" />
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

                {{-- ===== AKSI FORM ===== --}}
                <div class="flex justify-end space-x-4">
                    {{-- Tombol batal --}}
                    <x-button href="{{ route('admin.expenses.index') }}" variant="light">
                        Cancel
                    </x-button>

                    {{-- Tombol submit update --}}
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Expense
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript untuk menampilkan informasi kategori --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category-select');
    const categoryInfoItems = document.querySelectorAll('.category-info-item');
    const categoryGuideItems = document.querySelectorAll('.category-guide-item');
    
    // Function untuk menampilkan informasi kategori
    function showCategoryInfo(selectedCategory) {
        // Sembunyikan semua info kategori
        categoryInfoItems.forEach(item => {
            item.classList.add('hidden');
        });
        
        // Reset highlight pada guide items
        categoryGuideItems.forEach(item => {
            item.classList.remove('ring-2', 'ring-offset-2');
            const category = item.dataset.category;
            const colorMap = {
                'inventory': 'ring-purple-500',
                'operational': 'ring-orange-500',
                'salary': 'ring-emerald-500',
                'utilities': 'ring-cyan-500',
                'marketing': 'ring-pink-500',
                'maintenance': 'ring-amber-500',
                'other': 'ring-indigo-500'
            };
            if (colorMap[category]) {
                item.classList.remove(colorMap[category]);
            }
        });
        
        // Tampilkan info kategori yang dipilih
        if (selectedCategory) {
            const selectedInfo = document.querySelector(`[data-category="${selectedCategory}"]`);
            if (selectedInfo) {
                selectedInfo.classList.remove('hidden');
                
                // Tambahkan animasi fade in
                selectedInfo.style.opacity = '0';
                selectedInfo.style.transform = 'translateY(-10px)';
                
                setTimeout(() => {
                    selectedInfo.style.transition = 'all 0.3s ease-in-out';
                    selectedInfo.style.opacity = '1';
                    selectedInfo.style.transform = 'translateY(0)';
                }, 10);
            }
            
            // Highlight guide item yang dipilih
            const selectedGuide = document.querySelector(`.category-guide-item[data-category="${selectedCategory}"]`);
            if (selectedGuide) {
                const colorMap = {
                    'inventory': 'ring-purple-500',
                    'operational': 'ring-orange-500',
                    'salary': 'ring-emerald-500',
                    'utilities': 'ring-cyan-500',
                    'marketing': 'ring-pink-500',
                    'maintenance': 'ring-amber-500',
                    'other': 'ring-indigo-500'
                };
                selectedGuide.classList.add('ring-2', 'ring-offset-2', colorMap[selectedCategory] || 'ring-gray-500');
            }
        }
    }
    
    // Event listener untuk perubahan kategori
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            showCategoryInfo(this.value);
        });
        
        // Tampilkan info jika sudah ada nilai terpilih (untuk edit form)
        if (categorySelect.value) {
            showCategoryInfo(categorySelect.value);
        }
    }
});

// Function untuk memilih kategori dari guide
function selectCategory(category) {
    const categorySelect = document.getElementById('category-select');
    if (categorySelect) {
        categorySelect.value = category;
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        categorySelect.dispatchEvent(event);
        
        // Scroll ke form
        const formSection = document.querySelector('form');
        if (formSection) {
            formSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        // Show success feedback
        showCategorySelectedFeedback(category);
    }
}

// Function untuk menampilkan feedback ketika kategori dipilih
function showCategorySelectedFeedback(category) {
    const categoryLabels = {
        'inventory': 'Inventory & Supplies',
        'operational': 'Operational Costs',
        'salary': 'Salary & Benefits',
        'utilities': 'Utilities',
        'marketing': 'Marketing & Advertising',
        'maintenance': 'Maintenance & Repairs',
        'other': 'Other Expenses'
    };
    
    // Create temporary notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg z-50 transition-all duration-300';
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="font-medium">Category updated: ${categoryLabels[category] || category}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>
@endsection
