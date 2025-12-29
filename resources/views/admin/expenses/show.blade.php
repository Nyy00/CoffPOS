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
                            <div class="mt-1">
                                {{-- Mapping warna kategori yang konsisten --}}
                                @php
                                    $categoryColors = [
                                        'inventory' => 'purple',    
                                        'operational' => 'orange',   
                                        'salary' => 'emerald',        
                                        'utilities' => 'cyan',       
                                        'marketing' => 'pink',        
                                        'maintenance' => 'amber',     
                                        'other' => 'indigo'          
                                    ];
                                    
                                    $categoryLabels = [
                                        'inventory' => 'Inventori & Persediaan',
                                        'operational' => 'Biaya Operasional',
                                        'salary' => 'Gaji & Tunjangan',
                                        'utilities' => 'Utilitas (Listrik, Air, Internet)',
                                        'marketing' => 'Pemasaran & Iklan',
                                        'maintenance' => 'Pemeliharaan & Perbaikan',
                                        'other' => 'Pengeluaran Lainnya'
                                    ];
                                @endphp

                                <x-badge variant="{{ $categoryColors[$expense->category] ?? 'light' }}" size="lg">
                                    {{ $categoryLabels[$expense->category] ?? ucfirst($expense->category) }}
                                </x-badge>
                            </div>
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

                <!-- Informasi kategori dengan warna dan deskripsi -->
                <x-card title="Informasi Kategori">
                    @php
                        $categoryInfo = [
                            'inventory' => [
                                'description' => 'Biji kopi, gelas, serbet, bahan makanan, kemasan',
                                'examples' => ['Pembelian biji kopi', 'Gelas dan tutup sekali pakai', 'Bahan makanan', 'Kemasan', 'Serbet dan tisu'],
                                'color' => 'purple',
                                'bg' => 'bg-purple-50',
                                'text' => 'text-purple-800',
                                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'
                            ],
                            'operational' => [
                                'description' => 'Sewa, asuransi, izin, perizinan, jasa profesional',
                                'examples' => ['Pembayaran sewa bulanan', 'Asuransi bisnis', 'Jasa profesional', 'Izin dan perizinan', 'Biaya hukum'],
                                'color' => 'orange',
                                'bg' => 'bg-orange-50',
                                'text' => 'text-orange-800',
                                'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
                            ],
                            'salary' => [
                                'description' => 'Gaji karyawan, tunjangan, bonus, biaya pelatihan',
                                'examples' => ['Gaji bulanan', 'Tunjangan karyawan', 'Bonus kinerja', 'Program pelatihan', 'Pembayaran lembur'],
                                'color' => 'emerald',
                                'bg' => 'bg-emerald-50',
                                'text' => 'text-emerald-800',
                                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
                            ],
                            'utilities' => [
                                'description' => 'Listrik, air, gas, internet, telepon',
                                'examples' => ['Tagihan listrik', 'Tagihan air dan gas', 'Layanan internet', 'Layanan telepon', 'Pengelolaan limbah'],
                                'color' => 'cyan',
                                'bg' => 'bg-cyan-50',
                                'text' => 'text-cyan-800',
                                'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'
                            ],
                            'marketing' => [
                                'description' => 'Iklan, promosi, media sosial, biaya website',
                                'examples' => ['Iklan media sosial', 'Materi promosi', 'Pemeliharaan website', 'Kampanye pemasaran', 'Sponsor acara'],
                                'color' => 'pink',
                                'bg' => 'bg-pink-50',
                                'text' => 'text-pink-800',
                                'icon' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z'
                            ],
                            'maintenance' => [
                                'description' => 'Perbaikan peralatan, perlengkapan kebersihan, pemeliharaan fasilitas',
                                'examples' => ['Perbaikan peralatan', 'Perlengkapan kebersihan', 'Pemeliharaan fasilitas', 'Suku cadang', 'Kontrak pemeliharaan'],
                                'color' => 'amber',
                                'bg' => 'bg-amber-50',
                                'text' => 'text-amber-800',
                                'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
                            ],
                            'other' => [
                                'description' => 'Pengeluaran lain-lain yang tidak masuk kategori tertentu',
                                'examples' => ['Perlengkapan kantor', 'Pembelian lain-lain', 'Pengeluaran tak terduga', 'Biaya satu kali', 'Kebutuhan bisnis lainnya'],
                                'color' => 'indigo',
                                'bg' => 'bg-indigo-50',
                                'text' => 'text-indigo-800',
                                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                            ]
                        ];

                        $info = $categoryInfo[$expense->category] ?? $categoryInfo['other'];
                    @endphp

                    <div class="p-4 rounded-lg {{ $info['bg'] }} border border-{{ $info['color'] }}-200">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 {{ $info['bg'] }} rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 {{ $info['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h4 class="text-lg font-medium {{ $info['text'] }}">
                                        {{ $categoryLabels[$expense->category] ?? ucfirst($expense->category) }}
                                    </h4>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-{{ $info['color'] }}-100 text-{{ $info['color'] }}-800">
                                        Kategori {{ ucfirst($info['color']) }}
                                    </span>
                                </div>
                                
                                <p class="text-sm {{ $info['text'] }} mb-3 opacity-90">
                                    {{ $info['description'] }}
                                </p>

                                <div class="text-sm {{ $info['text'] }}">
                                    <strong>Contoh umum:</strong>
                                    <ul class="list-disc list-inside mt-1 space-y-1 opacity-75">
                                        @foreach($info['examples'] as $example)
                                            <li>{{ $example }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- ================= SIDEBAR ================= -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Receipt Card -->
                <x-card title="Receipt">
                    @if($expense->receipt_image)
                        <div class="text-center">
                            <div class="mb-4">
                                @if(Storage::exists($expense->receipt_image))
                                    <img src="{{ Storage::url($expense->receipt_image) }}" 
                                         alt="Receipt" 
                                         class="mx-auto max-w-full h-48 object-contain rounded-lg border">
                                @else
                                    <div class="mx-auto w-48 h-48 bg-gray-100 rounded-lg border flex items-center justify-center">
                                        <span class="text-gray-500">Receipt not found</span>
                                    </div>
                                @endif
                            </div>
                            <div class="space-y-2">
                                @if(Storage::exists($expense->receipt_image))
                                    <a href="{{ Storage::url($expense->receipt_image) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat Receipt
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Tidak ada receipt yang diupload</p>
                            <div class="mt-4">
                                <x-button href="{{ route('admin.expenses.edit', $expense) }}" variant="light" size="sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    Upload Receipt
                                </x-button>
                            </div>
                        </div>
                    @endif
                </x-card>

                <!-- Quick Actions -->
                <x-card title="Aksi Cepat">
                    <div class="space-y-3">
                        <x-button href="{{ route('admin.expenses.edit', $expense) }}" variant="primary" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Expense
                        </x-button>

                        <x-button href="{{ route('admin.expenses.create') }}" variant="light" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Expense Baru
                        </x-button>

                        <x-button variant="danger" class="w-full" onclick="confirmDelete('{{ $expense->id }}', '{{ addslashes($expense->description) }}', '{{ number_format($expense->amount, 0, ',', '.') }}')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Expense
                        </x-button>
                    </div>
                </x-card>

                <!-- Monthly Summary -->
                <x-card title="Ringkasan Bulanan">
                    @php
                        try {
                            $currentMonth = $expense->expense_date->format('M Y');
                            $monthlyTotal = \App\Models\Expense::whereYear('expense_date', $expense->expense_date->year)
                                                              ->whereMonth('expense_date', $expense->expense_date->month)
                                                              ->sum('amount') ?? 0;
                            
                            $categoryMonthlyTotal = \App\Models\Expense::whereYear('expense_date', $expense->expense_date->year)
                                                                      ->whereMonth('expense_date', $expense->expense_date->month)
                                                                      ->where('category', $expense->category)
                                                                      ->sum('amount') ?? 0;
                            
                            $categoryPercentage = $monthlyTotal > 0 ? ($categoryMonthlyTotal / $monthlyTotal) * 100 : 0;
                        } catch (\Exception $e) {
                            $currentMonth = 'N/A';
                            $monthlyTotal = 0;
                            $categoryMonthlyTotal = 0;
                            $categoryPercentage = 0;
                        }
                    @endphp

                    <div class="space-y-4">
                        <div>
                            <div class="text-sm font-medium text-gray-500">{{ $currentMonth }} Total</div>
                            <div class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($monthlyTotal, 0, ',', '.') }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">{{ $categoryLabels[$expense->category] ?? ucfirst($expense->category) }} Bulan Ini</div>
                            <div class="text-xl font-semibold text-gray-900">
                                Rp {{ number_format($categoryMonthlyTotal, 0, ',', '.') }}
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-gray-500">Persentase dari Total Bulanan</div>
                            <div class="flex items-center">
                                <div class="text-lg font-semibold text-gray-900">
                                    {{ number_format($categoryPercentage, 1) }}%
                                </div>
                                <div class="ml-2 flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-{{ $categoryColors[$expense->category] ?? 'gray' }}-500 h-2 rounded-full" 
                                         style="width: {{ min($categoryPercentage, 100) }}%"></div>
                                </div>
                            </div>
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
