<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('POS - Point of Sale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center py-16">
                        <div class="text-6xl mb-4">🛒</div>
                        <h3 class="text-2xl font-bold text-coffee-dark mb-2">POS System</h3>
                        <p class="text-gray-600">Point of Sale interface will be implemented in Week 7-8</p>
                        <div class="mt-8">
                            <a href="{{ route('dashboard') }}" class="bg-coffee-dark text-white px-6 py-3 rounded-lg hover:bg-coffee-brown transition">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
