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
                <div class="text-4xl mb-4">🎯</div>
                <h3 class="text-2xl font-bold text-coffee-dark mb-3">Our Mission</h3>
                <p class="text-gray-600">
                    To provide exceptional coffee experiences that brighten our customers' days, 
                    one cup at a time. We believe great coffee brings people together.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <div class="text-4xl mb-4">👁️</div>
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
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                    ⭐
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-2">Quality</h3>
                <p class="text-gray-600">Only the best beans and ingredients</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                    🤝
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-2">Community</h3>
                <p class="text-gray-600">Building connections through coffee</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                    💚
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-2">Sustainability</h3>
                <p class="text-gray-600">Caring for our planet and farmers</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">
                    🎨
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
