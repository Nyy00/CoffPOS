@extends('layouts.app')

@section('title', 'Customers Management')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Customers Management
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your coffee shop customers and loyalty program
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <x-button href="{{ route('admin.customers.create') }}" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Customer
                </x-button>
            </div>
        </div>

        <!-- Filters -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('admin.customers.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                <div class="flex-1">
                    <x-form.input 
                        name="search" 
                        placeholder="Search customers by name, email, or phone..."
                        value="{{ request('search') }}"
                        label="Search Customers"
                    />
                </div>
                <div class="w-full md:w-48">
                    <x-form.select 
                        name="points_filter" 
                        :options="['high' => 'High Points (>100)', 'medium' => 'Medium Points (50-100)', 'low' => 'Low Points (<50)']" 
                        value="{{ request('points_filter') }}"
                        placeholder="All Points Range"
                        label="Points Range"
                    />
                </div>
                <div class="flex space-x-2">
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </x-button>
                    @if(request()->hasAny(['search', 'points_filter']))
                        <x-button href="{{ route('admin.customers.index') }}" variant="light">
                            Clear
                        </x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Customers Table -->
        <x-card>
            @if($customers->count() > 0)
                <x-table :headers="[
                    ['label' => 'Customer', 'key' => 'name', 'sortable' => true],
                    ['label' => 'Contact', 'key' => 'contact'],
                    ['label' => 'Loyalty Points', 'key' => 'loyalty_points', 'sortable' => true],
                    ['label' => 'Total Spent', 'key' => 'total_spent', 'sortable' => true],
                    ['label' => 'Transactions', 'key' => 'transactions_count', 'sortable' => true],
                    ['label' => 'Joined', 'key' => 'created_at', 'sortable' => true],
                    ['label' => 'Actions', 'key' => 'actions']
                ]">
                    @foreach($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-600">
                                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                    @if($customer->date_of_birth)
                                        <div class="text-sm text-gray-500">Born: {{ $customer->date_of_birth->format('d M Y') }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                            @if($customer->phone)
                                <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900">{{ $customer->loyalty_points }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Rp {{ number_format($customer->transactions->sum('total_amount'), 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <x-badge variant="light">{{ $customer->transactions->count() }} orders</x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $customer->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <x-button href="{{ route('admin.customers.show', $customer) }}" variant="ghost" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </x-button>
                                <x-button href="{{ route('admin.customers.edit', $customer) }}" variant="ghost" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </x-button>
                                <x-button 
                                    variant="ghost" 
                                    size="sm"
                                    onclick="confirmDelete('{{ $customer->id }}', '{{ $customer->name }}', {{ $customer->transactions->count() }})"
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
                    <x-pagination :paginator="$customers" />
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No customers found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding your first customer.</p>
                    <div class="mt-6">
                        <x-button href="{{ route('admin.customers.create') }}" variant="primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Customer
                        </x-button>
                    </div>
                </div>
            @endif
        </x-card>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-modal-enhanced id="deleteModal" title="Delete Customer" type="danger">
    <p class="text-sm text-gray-500">
        Are you sure you want to delete <span id="customerName" class="font-medium"></span>?
    </p>
    <p id="transactionsWarning" class="text-sm text-red-600 mt-2 hidden">
        <strong>Warning:</strong> This customer has <span id="transactionsCount"></span> transactions. Deleting will affect transaction history.
    </p>
    <p class="text-sm text-gray-500 mt-2">This action cannot be undone.</p>
    
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
function confirmDelete(customerId, customerName, transactionsCount) {
    document.getElementById('customerName').textContent = customerName;
    document.getElementById('deleteForm').action = `/admin/customers/${customerId}`;
    
    const transactionsWarning = document.getElementById('transactionsWarning');
    const transactionsCountSpan = document.getElementById('transactionsCount');
    
    if (transactionsCount > 0) {
        transactionsCountSpan.textContent = transactionsCount;
        transactionsWarning.classList.remove('hidden');
    } else {
        transactionsWarning.classList.add('hidden');
    }
    
    openModal('deleteModal');
}
</script>
@endsection