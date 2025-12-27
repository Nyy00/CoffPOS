@extends('layouts.app') 
{{-- Menggunakan layout utama aplikasi --}}

@section('title', 'Users Management') 
{{-- Judul halaman --}}

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
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
            <form method="GET" action="{{ route('admin.users.index') }}"
                  class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">

                {{-- Input pencarian nama/email --}}
                <div class="flex-1">
                    <x-form.input 
                        name="search"
                        label="Search Users"
                        placeholder="Search users by name or email..."
                        value="{{ request('search') }}"
                    />
                </div>

                {{-- Filter role --}}
                <div class="w-full md:w-48">
                    <x-form.select 
                        name="role"
                        label="Role"
                        :options="['admin' => 'Admin', 'manager' => 'Manager', 'cashier' => 'Cashier']"
                        value="{{ request('role') }}"
                        placeholder="All Roles"
                    />
                </div>

                {{-- Tombol search & reset --}}
                <div class="flex space-x-2">
                    <x-button type="submit" variant="primary">
                        Search
                    </x-button>

                    {{-- Tombol clear filter --}}
                    @if(request()->hasAny(['search', 'role']))
                        <x-button href="{{ route('admin.users.index') }}" variant="light">
                            Clear
                        </x-button>
                    @endif
                </div>
            </form>
        </x-card>

        {{-- ================= TABEL USER ================= --}}
        <x-card>
            @if($users->count() > 0)

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
                                    <x-button 
                                        variant="ghost"
                                        size="sm"
                                        onclick="confirmDelete('{{ $user->id }}','{{ $user->name }}','{{ $user->role }}')"
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
<script>
function confirmDelete(userId, userName, userRole) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('userRole').textContent = userRole;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    openModal('deleteModal');
}
</script>

@endsection