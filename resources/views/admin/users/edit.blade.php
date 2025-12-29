@extends('layouts.app')
{{-- Menggunakan layout utama aplikasi --}}

@section('title', 'Edit User - ' . $user->name)
{{-- Judul halaman edit user, menampilkan nama user --}}

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
                                Edit {{ $user->name }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Judul dan deskripsi halaman --}}
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Update user information and permissions
                </p>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
            {{-- Proteksi CSRF --}}
            @csrf
            {{-- Method spoofing untuk update data --}}
            @method('PUT')

            <div class="space-y-6">

                <!-- Personal Information -->
                <x-card title="Personal Information">
                    {{-- Informasi dasar user --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Input nama lengkap --}}
                        <x-form.input 
                            name="name" 
                            label="Full Name"
                            placeholder="e.g., John Doe"
                            :value="$user->name"
                            required
                        />

                        {{-- Input email --}}
                        <x-form.input 
                            name="email" 
                            label="Email Address"
                            type="email"
                            placeholder="john@example.com"
                            :value="$user->email"
                            required
                        />
                    </div>

                    {{-- Peringatan jika user sedang mengedit akunnya sendiri --}}
                    @if($user->id === auth()->id())
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <div class="flex">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                          clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Editing Your Own Profile
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        You are editing your own user account. Be careful when changing your role or email address.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </x-card>

                <!-- Role & Permissions -->
                <x-card title="Role & Permissions">
                    {{-- Pengaturan role dan hak akses --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Dropdown pilihan role --}}
                        <div>
                            <x-form.select 
                                name="role" 
                                label="User Role"
                                :options="[
                                    'admin' => 'Admin - Full system access',
                                    'manager' => 'Manager - Management access',
                                    'cashier' => 'Cashier - POS access only'
                                ]"
                                :value="$user->role"
                                placeholder="Select a role"
                                required
                            />
                        </div>

                        {{-- Menampilkan role saat ini --}}
                        <div class="flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-sm text-gray-500">Current Role</div>

                                {{-- Warna role berdasarkan jenis --}}
                                @php
                                    $roleColors = [
                                        'admin' => 'text-red-600',
                                        'manager' => 'text-yellow-600',
                                        'cashier' => 'text-blue-600'
                                    ];
                                @endphp

                                <div class="text-lg font-medium {{ $roleColors[$user->role] ?? 'text-gray-600' }}">
                                    {{ ucfirst($user->role) }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    Last updated: {{ $user->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Peringatan khusus jika user memiliki role admin --}}
                    @if($user->role === 'admin')
                        <div class="mt-4 p-4 bg-red-50 rounded-lg">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                          clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Admin Role Warning
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        This user has admin privileges with full system access.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </x-card>

                <!-- Password Management -->
                <x-card title="Password Management">
                    {{-- Penggantian password user --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="password" 
                            label="New Password"
                            type="password"
                            placeholder="Leave empty to keep current password"
                        />

                        <x-form.input 
                            name="password_confirmation" 
                            label="Confirm New Password"
                            type="password"
                            placeholder="Repeat new password"
                        />
                    </div>
                </x-card>

                <!-- Avatar -->
                <x-card title="Profile Picture">
                    {{-- Upload avatar baru --}}
                    <x-form.file-upload 
                        name="avatar" 
                        label="Avatar"
                        accept="image/*"
                        :current-image="$user->avatar ? Storage::url($user->avatar) : ''"
                    />
                </x-card>

                <!-- User Statistics -->
                <x-card title="User Statistics">
                    {{-- Statistik dan status akun user --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $user->created_at->format('d M Y') }}
                            </div>
                            <div class="text-sm text-gray-500">Account Created</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                            </div>
                            <div class="text-sm text-gray-500">Last Login</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold {{ $user->email_verified_at ? 'text-green-600' : 'text-red-600' }}">
                                {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                            </div>
                            <div class="text-sm text-gray-500">Email Status</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $user->updated_at->diffForHumans() }}
                            </div>
                            <div class="text-sm text-gray-500">Last Updated</div>
                        </div>
                    </div>
                </x-card>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    {{-- Tombol batal --}}
                    <x-button href="{{ route('admin.users.index') }}" variant="light">
                        Cancel
                    </x-button>

                    {{-- Tombol submit update --}}
                    <x-button type="submit" variant="primary">
                        Update User
                    </x-button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection