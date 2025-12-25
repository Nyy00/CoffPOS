@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-500">
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
                            <a href="{{ route('admin.users.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Users</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-500">Add New User</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">Add New User</h1>
                <p class="mt-1 text-sm text-gray-500">Create a new system user account</p>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <!-- Personal Information -->
                <x-card title="Personal Information">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-form.input 
                            name="name" 
                            label="Full Name"
                            placeholder="e.g., John Doe"
                            required
                        />
                        
                        <x-form.input 
                            name="email" 
                            label="Email Address"
                            type="email"
                            placeholder="john@example.com"
                            required
                        />
                        
                        <x-form.input 
                            name="password" 
                            label="Password"
                            type="password"
                            placeholder="Minimum 8 characters"
                            required
                        />
                        
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        
                        <div class="flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-sm text-gray-500">Default Status</div>
                                <div class="text-lg font-medium text-green-600">Active</div>
                                <div class="text-xs text-gray-400">User will be activated immediately</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Role Descriptions -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-red-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                </svg>
                                <h4 class="text-sm font-medium text-red-800">Admin</h4>
                            </div>
                            <p class="mt-2 text-sm text-red-700">Full system access including user management, reports, and system settings.</p>
                        </div>
                        
                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                <h4 class="text-sm font-medium text-yellow-800">Manager</h4>
                            </div>
                            <p class="mt-2 text-sm text-yellow-700">Management access to products, customers, transactions, and reports.</p>
                        </div>
                        
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                <h4 class="text-sm font-medium text-blue-800">Cashier</h4>
                            </div>
                            <p class="mt-2 text-sm text-blue-700">POS system access only for processing transactions and customer service.</p>
                        </div>
                    </div>
                </x-card>

                <!-- Avatar -->
                <x-card title="Profile Picture">
                    <x-form.file-upload 
                        name="avatar" 
                        label="Avatar"
                        accept="image/*"
                    />
                    <p class="mt-2 text-sm text-gray-500">
                        Upload a profile picture for the user. Recommended size: 200x200px or larger.
                    </p>
                </x-card>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <x-button href="{{ route('admin.users.index') }}" variant="light">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create User
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection