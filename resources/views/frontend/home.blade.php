@extends('layouts.frontend')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-coffee-dark via-coffee-brown to-black text-white py-32 overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-gold/10 rounded-full -mr-48 -mt-48"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-gold/5 rounded-full -ml-48 -mb-48"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <div class="mb-6 flex items-center gap-3">
                    <svg class="w-8 h-8 text-gold animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z"/>
                    </svg>
                    <span class="text-gold font-semibold">Welcome to CoffPOS</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-bold font-heading mb-6 leading-tight">
                    Start Your Day with <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold to-yellow-400">Perfect Coffee</span>
                </h1>
                <p class="text-xl text-cream/80 mb-8 leading-relaxed">
                    Experience the finest coffee beans, expertly roasted and brewed to perfection. Every cup tells a story of quality and passion.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('menu') }}" class="bg-gradient-to-r from-gold to-yellow-400 text-coffee-dark px-8 py-4 rounded-full font-semibold text-lg hover:shadow-xl transition transform hover:scale-105 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        View Menu
                    </a>
                    <a href="{{ route('contact') }}" class="border-2 border-gold text-gold px-8 py-4 rounded-full font-semibold text-lg hover:bg-gold hover:text-coffee-dark transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contact Us
                    </a>
                </div>
            </div>
            <div class="hidden md:flex items-center justify-center">
                <div class="relative w-80 h-80">
                    <div class="absolute inset-0 bg-gradient-to-br from-gold/20 to-transparent rounded-full blur-3xl"></div>
                    <img src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400&h=400&fit=crop" alt="Coffee" class="w-full h-full object-cover rounded-3xl shadow-2xl">
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Popular Products -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="flex items-center justify-center gap-2 mb-4">
                <svg class="w-6 h-6 text-gold" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-gold font-semibold">Our Specialties</span>
            </div>
            <h2 class="text-5xl font-bold font-heading text-coffee-dark mb-4">Popular Menu</h2>
            <p class="text-xl text-gray-600">Customers' favorite picks crafted with passion</p>
        </div>

        @if($popularProducts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($popularProducts as $product)
            <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-2">
                <!-- Product Image -->
                <div class="relative h-56 bg-gradient-to-br from-light-coffee to-coffee-brown overflow-hidden">
                    @if($product->image)
                        @php
                            $fallbackImage = 'placeholder-product.png';
                            $productName = strtolower($product->name);
                            if (str_contains($productName, 'cheesecake')) $fallbackImage = 'cheesecake.jpg';
                            elseif (str_contains($productName, 'sandwich')) $fallbackImage = 'sandwich.jpg';
                            elseif (str_contains($productName, 'tiramisu')) $fallbackImage = 'tiramisu.jpg';
                            elseif (str_contains($productName, 'chocolate')) $fallbackImage = 'chocolate.jpg';
                            elseif (str_contains($productName, 'croissant')) $fallbackImage = 'croissants.jpg';
                            elseif (str_contains($productName, 'americano')) $fallbackImage = 'americano.jpg';
                            elseif (str_contains($productName, 'latte')) $fallbackImage = 'latte.jpg';
                            elseif (str_contains($productName, 'cappuccino')) $fallbackImage = 'cappuccino.jpg';
                            elseif (str_contains($productName, 'espresso')) $fallbackImage = 'espresso.jpg';
                            elseif (str_contains($productName, 'mocha')) $fallbackImage = 'mocha.jpg';
                            elseif (str_contains($productName, 'tea')) $fallbackImage = 'green-tea.jpg';
                        @endphp
                        <img src="{{ asset('images/products/' . str_replace('products/', '', $product->image)) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                             onerror="this.onerror=null; this.src='{{ asset('images/products/' . $fallbackImage) }}';">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-24 h-24 text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Badge -->
                    <div class="absolute top-3 right-3 flex gap-2">
                        <span class="bg-gold text-coffee-dark px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                            {{ $product->category->name }}
                        </span>
                        @if($product->is_available)
                            <span class="bg-green-500 text-white px-3 py-2 rounded-full text-xs font-semibold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Available
                            </span>
                        @else
                            <span class="bg-red-500 text-white px-3 py-2 rounded-full text-xs font-semibold">Sold Out</span>
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-coffee-dark mb-2 group-hover:text-gold transition">{{ $product->name }}</h3>
                    
                    @if($product->description)
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                    @endif
                    
                    <!-- Stock Info -->
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                        </svg>
                        <span class="text-sm text-gray-600">Stock: <span class="font-semibold text-coffee-dark">{{ $product->stock }}</span></span>
                    </div>

                    <!-- Price and Button -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Price</p>
                            <span class="text-2xl font-bold text-gold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('menu') }}" class="bg-coffee-dark text-cream px-6 py-3 rounded-full hover:bg-gold hover:text-coffee-dark transition transform hover:scale-105 flex items-center gap-2 font-semibold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Order
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-16">
            <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-coffee-dark to-coffee-brown text-cream px-10 py-4 rounded-full font-semibold text-lg hover:shadow-xl transition transform hover:scale-105">
                View Full Menu
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
        @else
        <div class="text-center py-16 bg-gray-50 rounded-2xl">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-gray-500 text-lg font-semibold">No products available at the moment</p>
            <p class="text-gray-400 mt-2">Please check back soon</p>
        </div>
        @endif
    </div>
</section>


<!-- Features Section -->
<section class="bg-gradient-to-r from-coffee-dark via-coffee-brown to-black text-white py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold font-heading mb-4">Why Choose CoffPOS?</h2>
            <p class="text-xl text-cream/80">We're committed to excellence in every cup</p>
        </div>

        <div class="grid md:grid-cols-3 gap-12">
            <!-- Feature 1 -->
            <div class="text-center transform hover:scale-105 transition duration-300">
                <div class="bg-gradient-to-br from-gold to-yellow-400 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-8 h-8 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gold mb-3">Premium Quality</h3>
                <p class="text-cream/80 leading-relaxed">Only the finest coffee beans, carefully selected and roasted to perfection for exceptional flavor</p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center transform hover:scale-105 transition duration-300">
                <div class="bg-gradient-to-br from-gold to-yellow-400 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-8 h-8 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gold mb-3">Fast Service</h3>
                <p class="text-cream/80 leading-relaxed">Quick and efficient service without compromising on quality or the experience</p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center transform hover:scale-105 transition duration-300">
                <div class="bg-gradient-to-br from-gold to-yellow-400 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-8 h-8 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gold mb-3">Made with Love</h3>
                <p class="text-cream/80 leading-relaxed">Every cup is crafted with passion and dedication by our skilled baristas</p>
            </div>
        </div>
    </div>
</section>


<!-- Testimonials Section -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="flex items-center justify-center gap-2 mb-4">
                <svg class="w-6 h-6 text-gold" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-gold font-semibold">Customer Reviews</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold font-heading text-coffee-dark mb-4">What Our Customers Say</h2>
            <p class="text-xl text-gray-600">Real reviews from real coffee lovers</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:scale-105">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-gold to-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        B
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-coffee-dark text-lg">Budi Santoso</h4>
                        <div class="flex gap-1 text-gold mt-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 italic leading-relaxed">"Best coffee in town! The atmosphere is cozy and the staff is very friendly. I come here every morning before work."</p>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:scale-105">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-gold to-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        S
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-coffee-dark text-lg">Siti Nurhaliza</h4>
                        <div class="flex gap-1 text-gold mt-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 italic leading-relaxed">"Love their cappuccino! Perfect blend and always consistent quality. The baristas really know their craft."</p>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:scale-105">
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-gold to-yellow-400 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                        A
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-coffee-dark text-lg">Ahmad Rizki</h4>
                        <div class="flex gap-1 text-gold mt-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 italic leading-relaxed">"Great place to work and enjoy good coffee. Perfect location and excellent WiFi. Highly recommended!"</p>
            </div>
        </div>
    </div>
</section>


<!-- CTA Section -->
<section class="bg-gradient-to-r from-coffee-brown to-coffee-dark text-white py-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-6 flex items-center justify-center gap-3">
            <svg class="w-7 h-7 text-gold" fill="currentColor" viewBox="0 0 20 20">
                <path d="M15 8a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path fill-rule="evenodd" d="M12.316 3.051a4 4 0 012.528 7.805 4 4 0 10-7.805 2.528A6 6 0 1018 10a6 6 0 01-5.684-6.949z" clip-rule="evenodd"/>
            </svg>
            <span class="text-gold font-semibold text-lg">Special Offer</span>
        </div>
        
        <h2 class="text-5xl md:text-6xl font-bold font-heading mb-6 leading-tight">
            Ready to Experience <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold to-yellow-400">Great Coffee?</span>
        </h2>
        
        <p class="text-2xl text-cream/90 mb-10">Visit us today or check out our full menu online</p>
        
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('menu') }}" class="bg-gradient-to-r from-gold to-yellow-400 text-coffee-dark px-10 py-4 rounded-full font-semibold text-lg hover:shadow-2xl transition transform hover:scale-105 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2z"/>
                </svg>
                View Full Menu
            </a>
            <a href="{{ route('contact') }}" class="border-2 border-gold text-gold px-10 py-4 rounded-full font-semibold text-lg hover:bg-gold hover:text-coffee-dark transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Find Us
            </a>
        </div>

        <p class="text-cream/60 mt-8 text-sm flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
            Open Daily | Coffee Enthusiasts Welcome | Fast & Friendly Service
        </p>
    </div>
</section>

@endsection
