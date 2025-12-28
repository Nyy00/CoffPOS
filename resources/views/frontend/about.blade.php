{{-- Menggunakan layout utama frontend --}}
@extends('layouts.frontend')

{{-- Judul halaman --}}
@section('title', 'About Us')

{{-- Konten utama halaman --}}
@section('content')

<section class="bg-gradient-to-br from-coffee-dark to-coffee-brown text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        {{-- Judul halaman --}}
        <h1 class="text-5xl font-bold font-heading mb-4">About CoffPOS</h1>

        {{-- Subjudul --}}
        <p class="text-xl text-cream/90">
            Our story, our passion, our coffee
        </p>
    </div>
</section>

<section class="py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Judul dan deskripsi cerita --}}
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold font-heading text-coffee-dark mb-6">
                Our Story
            </h2>
            <p class="text-lg text-gray-700 leading-relaxed">
                CoffPOS started in 2020 with a simple mission: to bring the finest coffee experience to our community.
                What began as a small coffee cart has grown into a beloved local coffee shop.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 mb-16">

            <div class="bg-white p-8 rounded-2xl shadow-lg">
                {{-- Icon --}}
                <div class="mb-4">
                    <svg class="w-12 h-12 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>

                {{-- Judul misi --}}
                <h3 class="text-2xl font-bold text-coffee-dark mb-3">
                    Our Mission
                </h3>

                {{-- Deskripsi misi --}}
                <p class="text-gray-600">
                    To provide exceptional coffee experiences that brighten our customers' days.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg">
                {{-- Icon --}}
                <div class="mb-4">
                    <svg class="w-12 h-12 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7z"/>
                    </svg>
                </div>

                {{-- Judul visi --}}
                <h3 class="text-2xl font-bold text-coffee-dark mb-3">
                    Our Vision
                </h3>

                {{-- Deskripsi visi --}}
                <p class="text-gray-600">
                    To become the most loved coffee shop in the region.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="bg-cream py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Judul values --}}
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold font-heading text-coffee-dark mb-4">
                Our Values
            </h2>
            <p class="text-lg text-gray-600">
                What we stand for
            </p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">

            {{-- Value: Quality (SUDAH DIPERBAIKI) --}}
            <div class="text-center group">
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4 transition transform group-hover:scale-110 duration-300">
                    <svg class="w-10 h-10 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-2">Quality</h3>
                <p class="text-gray-600">Only the best beans</p>
            </div>

            {{-- Value: Community (SUDAH DIPERBAIKI) --}}
            <div class="text-center group">
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4 transition transform group-hover:scale-110 duration-300">
                    <svg class="w-10 h-10 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-2">Community</h3>
                <p class="text-gray-600">Building connections</p>
            </div>

            {{-- Value: Sustainability (SUDAH DIPERBAIKI) --}}
            <div class="text-center group">
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4 transition transform group-hover:scale-110 duration-300">
                    <svg class="w-10 h-10 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-2">Sustainability</h3>
                <p class="text-gray-600">Caring for planet</p>
            </div>

            {{-- Value: Innovation (SUDAH DIPERBAIKI) --}}
            <div class="text-center group">
                <div class="w-20 h-20 bg-gold rounded-full flex items-center justify-center mx-auto mb-4 transition transform group-hover:scale-110 duration-300">
                    <svg class="w-10 h-10 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-2">Innovation</h3>
                <p class="text-gray-600">Always improving</p>
            </div>
        </div>
    </div>
</section>

<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Judul tim --}}
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold font-heading text-coffee-dark mb-4">
                Meet Our Team
            </h2>
            <p class="text-lg text-gray-600">
                The people behind your coffee
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">

            {{-- Member 1 --}}
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-light-coffee to-coffee-brown rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold">
                    JD
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-1">John Doe</h3>
                <p class="text-gold font-semibold mb-2">Head Barista</p>
                <p class="text-gray-600 text-sm">10 years experience</p>
            </div>

            {{-- Member 2 --}}
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-light-coffee to-coffee-brown rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold">
                    JS
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-1">Jane Smith</h3>
                <p class="text-gold font-semibold mb-2">Coffee Roaster</p>
                <p class="text-gray-600 text-sm">Roasting expert</p>
            </div>

            {{-- Member 3 --}}
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-light-coffee to-coffee-brown rounded-full mx-auto mb-4 flex items-center justify-center text-white text-4xl font-bold">
                    MB
                </div>
                <h3 class="text-xl font-bold text-coffee-dark mb-1">Mike Brown</h3>
                <p class="text-gold font-semibold mb-2">Store Manager</p>
                <p class="text-gray-600 text-sm">Customer experience</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-gradient-to-r from-coffee-brown to-coffee-dark text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        {{-- Judul CTA --}}
        <h2 class="text-4xl font-bold font-heading mb-4">
            Join Our Coffee Community
        </h2>

        {{-- Deskripsi CTA --}}
        <p class="text-xl text-cream/90 mb-8">
            Visit us today and experience the CoffPOS difference
        </p>

        {{-- Tombol menuju halaman contact --}}
        <a href="{{ route('contact') }}"
           class="inline-block bg-gold text-coffee-dark px-8 py-4 rounded-full font-semibold text-lg hover:bg-light-coffee transition">
            Get in Touch
        </a>
    </div>
</section>
@endsection