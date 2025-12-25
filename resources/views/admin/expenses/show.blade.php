@extends('layouts.app')

@section('title', 'Expense Details - ' . $expense->description)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.expenses.index') }}" class="text-gray-400 hover:text-gray-500">
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
                            <a href="{{ route('admin.expenses.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Expenses</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">{{ Str::limit($expense->description, 30) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $expense->description }}</h1>
                    <p class="mt-1 text-sm text-gray-500">Expense recorded on {{ $expense->expense_date->format('d M Y') }}</p>
                </div>
                <div class="flex space-x-3">
                    <x-button href="{{ route('admin.expenses.edit', $expense) }}" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Expense
                    </x-button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Expense Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <x-card title="Expense Information">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Amount</h4>
                            <p class="mt-1 text-2xl font-bold text-gray-900">Rp {{ number_format($expense->amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Category</h4>
                            <p class="mt-1">
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
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Expense Date</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $expense->expense_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Added By</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $expense->user->name }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Created At</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $expense->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Last Updated</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $expense->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($expense->notes)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500">Additional Notes</h4>
                        <p class="mt-2 text-sm text-gray-900">{{ $expense->notes }}</p>
                    </div>
                    @endif
                </x-card>

                <!-- Category Context -->
                <x-card title="Category Information">
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
                        <h4 class="text-sm font-medium text-{{ $info['color'] }}-800 mb-2">{{ ucfirst($expense->category) }} Category</h4>
                        <p class="text-sm text-{{ $info['color'] }}-700 mb-3">{{ $info['description'] }}</p>
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

            <!-- Receipt & Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Receipt Display -->
                <x-card title="Receipt">
                    @if($expense->receipt_image)
                        <div class="text-center">
                            @if(Str::endsWith($expense->receipt_image, '.pdf'))
                                <!-- PDF Receipt -->
                                <div class="p-8 bg-gray-100 rounded-lg">
                                    <svg class="mx-auto h-16 w-16 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600 mb-4">PDF Receipt</p>
                                    <x-button href="{{ Storage::url($expense->receipt_image) }}" target="_blank" variant="primary" size="sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        View PDF
                                    </x-button>
                                </div>
                            @else
                                <!-- Image Receipt -->
                                <img src="{{ Storage::url($expense->receipt_image) }}" alt="Receipt" class="w-full rounded-lg shadow-sm">
                                <div class="mt-4">
                                    <x-button href="{{ Storage::url($expense->receipt_image) }}" target="_blank" variant="light" size="sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        View Full Size
                                    </x-button>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No receipt uploaded</p>
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
                <x-card title="Quick Actions">
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
                            Add New Expense
                        </x-button>
                        
                        <x-button 
                            onclick="confirmDelete('{{ $expense->id }}', '{{ $expense->description }}', '{{ number_format($expense->amount, 0, ',', '.') }}')"
                            variant="outline-danger" 
                            class="w-full"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Expense
                        </x-button>
                    </div>
                </x-card>

                <!-- Expense Summary -->
                <x-card title="Monthly Summary">
                    @php
                        $monthlyTotal = \App\Models\Expense::whereMonth('expense_date', $expense->expense_date->month)
                            ->whereYear('expense_date', $expense->expense_date->year)
                            ->sum('amount');
                        $categoryTotal = \App\Models\Expense::where('category', $expense->category)
                            ->whereMonth('expense_date', $expense->expense_date->month)
                            ->whereYear('expense_date', $expense->expense_date->year)
                            ->sum('amount');
                    @endphp
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ $expense->expense_date->format('M Y') }} Total</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                Rp {{ number_format($monthlyTotal, 0, ',', '.') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ ucfirst($expense->category) }} This Month</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                Rp {{ number_format($categoryTotal, 0, ',', '.') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Percentage of Monthly</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $monthlyTotal > 0 ? number_format(($expense->amount / $monthlyTotal) * 100, 1) : 0 }}%
                            </dd>
                        </div>
                    </dl>
                </x-card>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-modal-enhanced id="deleteModal" title="Delete Expense" type="danger">
    <p class="text-sm text-gray-500">
        Are you sure you want to delete the expense "<span id="expenseDescription" class="font-medium"></span>" 
        with amount Rp <span id="expenseAmount" class="font-medium"></span>?
    </p>
    <p class="text-sm text-gray-500 mt-2">This action cannot be undone and will affect your expense reports.</p>
    
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
function confirmDelete(expenseId, expenseDescription, expenseAmount) {
    document.getElementById('expenseDescription').textContent = expenseDescription;
    document.getElementById('expenseAmount').textContent = expenseAmount;
    document.getElementById('deleteForm').action = `/admin/expenses/${expenseId}`;
    openModal('deleteModal');
}
</script>
@endsection