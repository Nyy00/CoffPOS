@extends('layouts.app')
{{-- Menggunakan layout utama aplikasi --}}

@section('title', 'Add New User')
{{-- Judul halaman tambah user --}}

@section('content')
<div class="py-6">
    {{-- Wrapper utama halaman --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6">
            {{-- Breadcrumb navigasi --}}
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">

                    {{-- Tombol kembali ke halaman daftar user --}}
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-500">
                            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z"
                                      clip-rule="evenodd" />
                            </svg>
                            <span class="sr-only">Back</span>
                        </a>
                    </li>

                    {{-- Breadcrumb ke halaman Users --}}
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('admin.users.index') }}"
                               class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Users
                            </a>
                        </div>
                    </li>

                    {{-- Breadcrumb halaman aktif --}}
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                      clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">
                                Add New User
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Judul dan deskripsi halaman --}}
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Add New User</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Create a new system user account
                </p>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            {{-- Token keamanan --}}
            @csrf

            <div class="space-y-6">

                <!-- Personal Information -->
                <x-card title="Personal Information">
                    {{-- Informasi dasar user --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Nama lengkap --}}
                        <x-form.input 
                            name="name" 
                            label="Full Name"
                            placeholder="e.g., John Doe"
                            required
                        />

                        {{-- Email --}}
                        <x-form.input 
                            name="email" 
                            label="Email Address"
                            type="email"
                            placeholder="john@example.com"
                            required
                        />

                        {{-- Password --}}
                        <x-form.input 
                            name="password" 
                            label="Password"
                            type="password"
                            placeholder="Minimum 8 characters"
                            required
                        />

                        {{-- Konfirmasi password --}}
                        <x-form.input 
                            name="password_confirmation" 
                            label="Confirm Password"
                            type="password"
                            placeholder="Repeat password"
                            required
                        />
                    </div>
                </x-card>

                <!-- Role & Permissions -->
                <x-card title="Role & Permissions">
                    {{-- Pengaturan role dan status user --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Pilihan role user --}}
                        <div>
                            <x-form.select 
                                name="role" 
                                label="User Role"
                                :options="[
                                    'admin' => 'Admin - Full system access',
                                    'manager' => 'Manager - Management access',
                                    'cashier' => 'Cashier - POS access only'
                                ]"
                                placeholder="Select a role"
                                required
                            />
                        </div>

                        {{-- Informasi status default --}}
                        <div class="flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-sm text-gray-500">Default Status</div>
                                <div class="text-lg font-medium text-green-600">Active</div>
                                <div class="text-xs text-gray-400">
                                    User will be activated immediately
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi masing-masing role --}}
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">

                        {{-- Role Admin --}}
                        <div class="p-4 bg-red-50 rounded-lg">
                            <h4 class="text-sm font-medium text-red-800">Admin</h4>
                            <p class="mt-2 text-sm text-red-700">
                                Full system access including user management, reports, and system settings.
                            </p>
                        </div>

                        {{-- Role Manager --}}
                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <h4 class="text-sm font-medium text-yellow-800">Manager</h4>
                            <p class="mt-2 text-sm text-yellow-700">
                                Management access to products, customers, transactions, and reports.
                            </p>
                        </div>

                        {{-- Role Cashier --}}
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-800">Cashier</h4>
                            <p class="mt-2 text-sm text-blue-700">
                                POS system access only for processing transactions and customer service.
                            </p>
                        </div>
                    </div>
                </x-card>

                <!-- Avatar -->
                <x-card title="Profile Picture">
                    {{-- Upload foto profil user --}}
                    <x-form.file-upload 
                        name="avatar" 
                        label="Avatar"
                        accept="image/*"
                    />
                    <p class="mt-2 text-sm text-gray-500">
                        Upload a profile picture for the user.
                    </p>
                </x-card>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    {{-- Tombol batal --}}
                    <x-button href="{{ route('admin.users.index') }}" variant="light">
                        Cancel
                    </x-button>

                    {{-- Tombol submit --}}
                    <x-button type="submit" variant="primary">
                        Create User
                    </x-button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection