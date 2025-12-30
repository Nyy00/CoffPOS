@extends('layouts.app') 
{{-- Menggunakan layout utama aplikasi --}}

@section('title', 'Users Management') 
{{-- Judul halaman --}}

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- ================= ALERT MESSAGES ================= --}}
        @if(session('success'))
            <x-alert type="success" class="mb-6">
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="error" class="mb-6">
                {{ session('error') }}
            </x-alert>
        @endif
        
        {{-- ================= HEADER HALAMAN ================= --}}
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                {{-- Judul utama --}}
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Users Management
                </h2>
                {{-- Deskripsi halaman --}}
                <p class="mt-1 text-sm text-gray-500">
                    Manage system users and their roles
                </p>
            </div>

            {{-- Tombol tambah user --}}
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <x-button href="{{ route('admin.users.create') }}" variant="primary">
                    {{-- Icon tambah --}}
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add User
                </x-button>
            </div>
        </div>

        {{-- ================= FILTER & SEARCH ================= --}}
        <x-card class="mb-6">
            {{-- Form pencarian user --}}
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Input pencarian nama/email --}}
                    <div>
                        <x-form.input 
                            name="search"
                            label="Search Users"
                            placeholder="Search users by name or email..."
                            value="{{ request('search') }}"
                        />
                    </div>

                    {{-- Filter role --}}
                    <div>
                        <x-form.select 
                            name="role"
                            label="Role"
                            :options="['admin' => 'Admin', 'manager' => 'Manager', 'cashier' => 'Cashier']"
                            value="{{ request('role') }}"
                            placeholder="All Roles"
                        />
                    </div>

                    {{-- Tombol search & reset --}}
                    <div class="flex items-end space-x-2">
                        <x-button type="submit" variant="primary" class="flex-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </x-button>

                        {{-- Tombol clear filter --}}
                        @if(request()->hasAny(['search', 'role']))
                            <x-button href="{{ route('admin.users.index') }}" variant="light">
                                Clear
                            </x-button>
                        @endif
                    </div>
                </div>
            </form>
        </x-card>

        {{-- ================= TABEL USER ================= --}}
        <x-card>
            @if($users->isNotEmpty())

                {{-- Komponen tabel --}}
                <x-table :headers="[
                    ['label' => 'User', 'key' => 'name'],
                    ['label' => 'Email', 'key' => 'email'],
                    ['label' => 'Role', 'key' => 'role'],
                    ['label' => 'Status', 'key' => 'status'],
                    ['label' => 'Last Login', 'key' => 'last_login'],
                    ['label' => 'Created', 'key' => 'created_at'],
                    ['label' => 'Actions', 'key' => 'actions']
                ]">

                    {{-- Loop data user --}}
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">

                        {{-- Kolom User --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                {{-- Avatar --}}
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}"
                                             class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        {{-- Inisial nama jika tidak ada avatar --}}
                                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Nama user --}}
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>

                                    {{-- Penanda akun sendiri --}}
                                    @if($user->id === auth()->id())
                                        <div class="text-sm text-blue-600">(You)</div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $user->email }}
                        </td>

                        {{-- Role --}}
                        <td class="px-6 py-4">
                            <x-badge>
                                {{ ucfirst($user->role) }}
                            </x-badge>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            @if($user->email_verified_at)
                                <x-badge variant="success">Active</x-badge>
                            @else
                                <x-badge variant="warning">Pending</x-badge>
                            @endif
                        </td>

                        {{-- Last login --}}
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </td>

                        {{-- Tanggal dibuat --}}
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex space-x-2 justify-end">
                                {{-- Edit --}}
                                <x-button href="{{ route('admin.users.edit', $user) }}" variant="ghost" size="sm">
                                    Edit
                                </x-button>

                                {{-- Delete (kecuali akun sendiri) --}}
                                @if($user->id !== auth()->id())
                                    @php
                                        $canDelete = !$user->transactions()->exists() && !$user->expenses()->exists();
                                        $transactionCount = $user->transactions()->count();
                                        $expenseCount = $user->expenses()->count();
                                    @endphp
                                    <x-button 
                                        variant="ghost"
                                        size="sm"
                                        onclick="confirmDelete('{{ $user->id }}','{{ $user->name }}','{{ $user->role }}', {{ $canDelete ? 'true' : 'false' }}, {{ $transactionCount }}, {{ $expenseCount }})"
                                        class="text-red-600">
                                        Delete
                                    </x-button>
                                @endif
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </x-table>

                {{-- Pagination --}}
                <div class="mt-6">
                    <x-pagination :paginator="$users" />
                </div>

            @else
                {{-- Tampilan jika data kosong --}}
                <div class="text-center py-12">
                    <p class="text-gray-500">No users found</p>
                </div>
            @endif
        </x-card>
    </div>
</div>

{{-- ================= SCRIPT DELETE MODAL ================= --}}

{{-- Modal konfirmasi hapus user --}}
<x-modal-enhanced id="deleteModal" title="Delete User" type="danger">
    <p class="text-sm text-gray-500">
        Are you sure you want to delete user <span id="userName" class="font-medium"></span> 
        with role <span id="userRole" class="font-medium"></span>?
    </p>
    <p class="text-sm text-red-600 mt-2">
        This action cannot be undone. All data associated with this user will be permanently deleted.
    </p>
    <div id="deleteWarning" class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md hidden">
        <p class="text-sm text-yellow-800">
            <strong>Note:</strong> Users with transaction or expense history cannot be deleted for data integrity.
        </p>
    </div>

    <x-slot name="footer">
        <form id="deleteForm" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="danger" id="deleteButton">Delete</x-button>
        </form>
        <x-button type="button" variant="light" onclick="closeModal('deleteModal')" class="ml-3">
            Cancel
        </x-button>
    </x-slot>
</x-modal-enhanced>

<script>
function confirmDelete(userId, userName, userRole, canDelete, transactionCount, expenseCount) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('userRole').textContent = userRole;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    
    const deleteButton = document.getElementById('deleteButton');
    const deleteWarning = document.getElementById('deleteWarning');
    
    if (!canDelete) {
        // Show warning and disable delete button
        deleteWarning.classList.remove('hidden');
        deleteButton.disabled = true;
        deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
        
        let warningText = 'This user cannot be deleted because they have ';
        if (transactionCount > 0 && expenseCount > 0) {
            warningText += `${transactionCount} transaction(s) and ${expenseCount} expense(s).`;
        } else if (transactionCount > 0) {
            warningText += `${transactionCount} transaction(s).`;
        } else if (expenseCount > 0) {
            warningText += `${expenseCount} expense(s).`;
        }
        
        deleteWarning.querySelector('p').innerHTML = `<strong>Cannot Delete:</strong> ${warningText}`;
    } else {
        // Hide warning and enable delete button
        deleteWarning.classList.add('hidden');
        deleteButton.disabled = false;
        deleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    openModal('deleteModal');
}
</script>

@endsection