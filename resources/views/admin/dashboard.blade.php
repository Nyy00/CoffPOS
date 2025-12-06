<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-4">Welcome, {{ auth()->user()->name }}!</h3>
                    <p class="text-gray-600 mb-4">Role: <span class="font-semibold text-gold">{{ ucfirst(auth()->user()->role) }}</span></p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        <!-- Stats Card -->
                        <div class="bg-gradient-to-br from-coffee-dark to-coffee-brown text-white p-6 rounded-xl shadow-lg">
                            <div class="text-3xl mb-2">📊</div>
                            <h4 class="text-lg font-semibold mb-1">Total Products</h4>
                            <p class="text-3xl font-bold">{{ \App\Models\Product::count() }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-gold to-light-coffee text-white p-6 rounded-xl shadow-lg">
                            <div class="text-3xl mb-2">👥</div>
                            <h4 class="text-lg font-semibold mb-1">Total Customers</h4>
                            <p class="text-3xl font-bold">{{ \App\Models\Customer::count() }}</p>
                        </div>

                        <div class="bg-gradient-to-br from-light-coffee to-coffee-brown text-white p-6 rounded-xl shadow-lg">
                            <div class="text-3xl mb-2">📦</div>
                            <h4 class="text-lg font-semibold mb-1">Categories</h4>
                            <p class="text-3xl font-bold">{{ \App\Models\Category::count() }}</p>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-cream rounded-xl">
                        <h4 class="text-lg font-bold text-coffee-dark mb-4">Quick Actions</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <a href="#" class="bg-white p-4 rounded-lg text-center hover:shadow-lg transition">
                                <div class="text-2xl mb-2">📦</div>
                                <p class="text-sm font-semibold text-coffee-dark">Products</p>
                            </a>
                            <a href="#" class="bg-white p-4 rounded-lg text-center hover:shadow-lg transition">
                                <div class="text-2xl mb-2">👥</div>
                                <p class="text-sm font-semibold text-coffee-dark">Customers</p>
                            </a>
                            <a href="#" class="bg-white p-4 rounded-lg text-center hover:shadow-lg transition">
                                <div class="text-2xl mb-2">📊</div>
                                <p class="text-sm font-semibold text-coffee-dark">Reports</p>
                            </a>
                            <a href="#" class="bg-white p-4 rounded-lg text-center hover:shadow-lg transition">
                                <div class="text-2xl mb-2">⚙️</div>
                                <p class="text-sm font-semibold text-coffee-dark">Settings</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
