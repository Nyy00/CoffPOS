@extends('layouts.app')

@section('title', 'Users Management')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Users Management
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage system users and their roles
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <x-button href="{{ route('admin.users.create') }}" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add User
                </x-button>
            </div>
        </div>

        <!-- Filters -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
                <div class="flex-1">
                    <x-form.input 
                        name="search" 
                        placeholder="Search users by name or email..."
                        value="{{ request('search') }}"
                        label="Search Users"
                    />
                </div>
                <div class="w-full md:w-48">
                    <x-form.select 
                        name="role" 
                        :options="['admin' => 'Admin', 'manager' => 'Manager', 'cashier' => 'Cashier']" 
                        value="{{ request('role') }}"
                        placeholder="All Roles"
                        label="Role"
                    />
                </div>
                <div class="flex space-x-2">
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </x-button>
                    @if(request()->hasAny(['search', 'role']))
                        <x-button href="{{ route('admin.users.index') }}" variant="light">
                            Clear
                        </x-button>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Users Table -->
        <x-card>
            @if($users->count() > 0)
                <x-table :headers="[
                    ['label' => 'User', 'key' => 'name', 'sortable' => true],
                    ['label' => 'Email', 'key' => 'email', 'sortable' => true],
                    ['label' => 'Role', 'key' => 'role', 'sortable' => true],
                    ['label' => 'Status', 'key' => 'status'],
                    ['label' => 'Last Login', 'key' => 'last_login', 'sortable' => true],
                    ['label' => 'Created', 'key' => 'created_at', 'sortable' => true],
                    ['label' => 'Actions', 'key' => 'actions']
                ]">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <div class="text-sm text-blue-600">(You)</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $roleColors = [
                                    'admin' => 'danger',
                                    'manager' => 'warning', 
                                    'cashier' => 'info'
                                ];
                            @endphp
                            <x-badge variant="{{ $roleColors[$user->role] ?? 'light' }}">
                                {{ ucfirst($user->role) }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->email_verified_at)
                                <x-badge variant="success">Active</x-badge>
                            @else
                                <x-badge variant="warning">Pending</x-badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <x-button href="{{ route('admin.users.edit', $user) }}" variant="ghost" size="sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </x-button>
                                @if($user->id !== auth()->id())
                                    <x-button 
                                        variant="ghost" 
                                        size="sm"
                                        onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}', '{{ $user->role }}')"
                                        class="text-red-600 hover:text-red-800"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </x-button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </x-table>

                <!-- Pagination -->
                <div class="mt-6">
                    <x-pagination :paginator="$users" />
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding a new user.</p>
                    <div class="mt-6">
                        <x-button href="{{ route('admin.users.create') }}" variant="primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add User
                        </x-button>
                    </div>
                </div>
            @endif
        </x-card>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<x-modal-enhanced id="deleteModal" title="Delete User" type="danger">
    <p class="text-sm text-gray-500">
        Are you sure you want to delete <span id="userName" class="font-medium"></span>?
    </p>
    <p id="roleWarning" class="text-sm text-red-600 mt-2">
        <strong>Warning:</strong> This user has <span id="userRole" class="font-medium"></span> role. Deleting will remove their access immediately.
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
function confirmDelete(userId, userName, userRole) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('userRole').textContent = userRole;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    openModal('deleteModal');
}
</script>
@endsection