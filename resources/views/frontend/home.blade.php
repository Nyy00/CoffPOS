@extends('layouts.frontend')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-coffee-dark to-coffee-brown text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-5xl md:text-6xl font-bold font-heading mb-6">
                    Start Your Day with <span class="text-gold">Perfect Coffee</span>
                </h1>
                <p class="text-xl text-cream/90 mb-8">
                    Experience the finest coffee beans, expertly roasted and brewed to perfection. Every cup tells a story.
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('menu') }}" class="bg-gold text-coffee-dark px-8 py-4 rounded-full font-semibold text-lg hover:bg-light-coffee transition transform hover:scale-105">
                        View Menu
                    </a>
                    <a href="{{ route('contact') }}" class="border-2 border-gold text-gold px-8 py-4 rounded-full font-semibold text-lg hover:bg-gold hover:text-coffee-dark transition">
                        Contact Us
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="text-9xl text-center">☕</div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Products -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold font-heading text-coffee-dark mb-4">Popular Menu</h2>
            <p class="text-lg text-gray-600">Our customers' favorite picks</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($popularProducts as $product)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="h-48 bg-gradient-to-br from-light-coffee to-coffee-brown flex items-center justify-center">
                    <span class="text-6xl">☕</span>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xl font-bold text-coffee-dark">{{ $product->name }}</h3>
                        <span class="bg-gold/20 text-coffee-dark px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $product->category->name }}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 80) }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-gold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @if($product->is_available)
                            <span class="text-green-600 text-sm font-semibold">Available</span>
                        @else
                            <span class="text-red-600 text-sm font-semibold">Sold Out</span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-500">No products available</p>
            </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('menu') }}" class="inline-block bg-coffee-dark text-cream px-8 py-4 rounded-full font-semibold hover:bg-coffee-brown transition">
                View Full Menu →
            </a>
        </div>
    </div>
</section>

<!-- Features -->
<section class="bg-coffee-dark text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-12">
            <div class="text-center">
                <div class="text-5xl mb-4">🌟</div>
                <h3 class="text-2xl font-bold text-gold mb-3">Premium Quality</h3>
                <p class="text-cream/80">Only the finest coffee beans, carefully selected and roasted</p>
            </div>
            <div class="text-center">
                <div class="text-5xl mb-4">⚡</div>
                <h3 class="text-2xl font-bold text-gold mb-3">Fast Service</h3>
                <p class="text-cream/80">Quick and efficient service without compromising quality</p>
            </div>
            <div class="text-center">
                <div class="text-5xl mb-4">❤️</div>
                <h3 class="text-2xl font-bold text-gold mb-3">Made with Love</h3>
                <p class="text-cream/80">Every cup is crafted with passion and dedication</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold font-heading text-coffee-dark mb-4">What Our Customers Say</h2>
            <p class="text-lg text-gray-600">Real reviews from real coffee lovers</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center text-white font-bold text-xl">
                        B
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-coffee-dark">Budi Santoso</h4>
                        <div class="text-gold">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
                <p class="text-gray-600 italic">"Best coffee in town! The atmosphere is cozy and the staff is very friendly."</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center text-white font-bold text-xl">
                        S
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-coffee-dark">Siti Nurhaliza</h4>
                        <div class="text-gold">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
                <p class="text-gray-600 italic">"Love their cappuccino! Perfect blend and always consistent quality."</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center text-white font-bold text-xl">
                        A
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-coffee-dark">Ahmad Rizki</h4>
                        <div class="text-gold">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
                <p class="text-gray-600 italic">"Great place to work and enjoy good coffee. Highly recommended!"</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-coffee-brown to-coffee-dark text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold font-heading mb-4">Ready to Experience Great Coffee?</h2>
        <p class="text-xl text-cream/90 mb-8">Visit us today or check out our full menu online</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('menu') }}" class="bg-gold text-coffee-dark px-8 py-4 rounded-full font-semibold text-lg hover:bg-light-coffee transition">
                View Menu
            </a>
            <a href="{{ route('contact') }}" class="border-2 border-gold text-gold px-8 py-4 rounded-full font-semibold text-lg hover:bg-gold hover:text-coffee-dark transition">
                Find Us
            </a>
        </div>
    </div>
</section>
@endsection
