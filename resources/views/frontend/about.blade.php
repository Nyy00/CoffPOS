@extends('layouts.frontend')

@section('title', 'About Us')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-coffee-dark to-coffee-brown text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl font-bold font-heading mb-4">About CoffPOS</h1>
        <p class="text-xl text-cream/90">Our story, our passion, our coffee</p>
    </div>
</section>

<!-- Our Story -->
<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold font-heading text-coffee-dark mb-6">Our Story</h2>
            <p class="text-lg text-gray-700 leading-relaxed">
                CoffPOS started in 2020 with a simple mission: to bring the finest coffee experience to our community. 
                What began as a small coffee cart has grown into a beloved local coffee shop, serving hundreds of happy customers every day.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 mb-16">
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <div class="mb-4">
                    <svg class="w-12 h-12 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-coffee-dark mb-3">Our Mission</h3>
                <p class="text-gray-600">
                    To provide exceptional coffee experiences that brighten our customers' days, 
                    one cup at a time. We believe great coffee brings people together.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <div class="mb-4">
                    <svg class="w-12 h-12 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-coffee-dark mb-3">Our Vision</h3>
                <p class="text-gray-600">
                    To become the most loved coffee shop in the region, known for quality, 
                    consistency, and warm hospitality that makes everyone feel at home.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="bg-cream py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold font-heading text-coffee-dark mb-4">Our Values</h2>
            <p class="text-lg text-gray-600">What we stand for</p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-coffee-dark" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-2">Quality</h3>
                <p class="text-gray-600">Only the best beans and ingredients</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m4 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-2">Community</h3>
                <p class="text-gray-600">Building connections through coffee</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-2">Sustainability</h3>
                <p class="text-gray-600">Caring for our planet and farmers</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-2">Innovation</h3>
                <p class="text-gray-600">Always improving and evolving</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Team -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold font-heading text-coffee-dark mb-4">Meet Our Team</h2>
            <p class="text-lg text-gray-600">The passionate people behind your perfect cup</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-light-coffee to-coffee-brown rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold">
                    JD
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-1">John Doe</h3>
                <p class="text-gold font-semibold mb-2">Head Barista</p>
                <p class="text-gray-600 text-sm">10 years of coffee expertise</p>
            </div>

            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-light-coffee to-coffee-brown rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold">
                    JS
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-1">Jane Smith</h3>
                <p class="text-gold font-semibold mb-2">Coffee Roaster</p>
                <p class="text-gray-600 text-sm">Master of roasting perfection</p>
            </div>

            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-light-coffee to-coffee-brown rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold">
                    MB
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-1">Mike Brown</h3>
                <p class="text-gold font-semibold mb-2">Store Manager</p>
                <p class="text-gray-600 text-sm">Ensuring great experiences</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="bg-gradient-to-r from-coffee-brown to-coffee-dark text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold font-heading mb-4">Join Our Coffee Community</h2>
        <p class="text-xl text-cream/90 mb-8">Visit us today and experience the CoffPOS difference</p>
        <a href="{{ route('contact') }}" class="inline-block bg-gold text-coffee-dark px-8 py-4 rounded-full font-semibold text-lg hover:bg-light-coffee transition">
            Get in Touch
        </a>
    </div>
</section>
@endsection