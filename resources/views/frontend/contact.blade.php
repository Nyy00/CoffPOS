{{-- Menggunakan layout utama frontend --}}
@extends('layouts.frontend')

{{-- Judul halaman --}}
@section('title', 'Contact Us')

{{-- Konten utama halaman --}}
@section('content')

<!-- ================= HERO SECTION ================= -->
<!-- Banner / header halaman Contact -->
<section class="bg-gradient-to-br from-coffee-dark to-coffee-brown text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        {{-- Judul halaman --}}
        <h1 class="text-5xl font-bold font-heading mb-4">Contact Us</h1>

        {{-- Subjudul --}}
        <p class="text-xl text-cream/90">We'd love to hear from you</p>
    </div>
</section>

<!-- ================= CONTACT INFO SECTION ================= -->
<!-- Informasi kontak CoffPOS -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Grid utama (tanpa form) --}}
        <div class="grid md:grid-cols-1 gap-12">

            <div>
                {{-- Judul section --}}
                <h2 class="text-3xl font-bold font-heading text-coffee-dark mb-6">
                    Get in Touch
                </h2>

                {{-- Deskripsi --}}
                <p class="text-gray-600 mb-8">
                    Have a question or want to visit us? We're here to help!
                </p>

                <!-- ================= DETAIL KONTAK ================= -->
                <div class="space-y-6">

                    {{-- Address --}}
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center flex-shrink-0">
                            {{-- Icon lokasi --}}
                            <svg class="w-6 h-6 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-bold text-coffee-dark mb-1">Address</h3>
                            <p class="text-gray-600">
                                Jl. Merdeka No. 123<br>
                                Bandung, West Java 40111
                            </p>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center flex-shrink-0">
                            {{-- Icon telepon --}}
                            <svg class="w-6 h-6 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-bold text-coffee-dark mb-1">Phone</h3>
                            <p class="text-gray-600">+62 812-3456-7890</p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center flex-shrink-0">
                            {{-- Icon email --}}
                            <svg class="w-6 h-6 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-bold text-coffee-dark mb-1">Email</h3>
                            <p class="text-gray-600">info@coffpos.com</p>
                        </div>
                    </div>

                    {{-- Opening Hours --}}
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center flex-shrink-0">
                            {{-- Icon jam --}}
                            <svg class="w-6 h-6 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-bold text-coffee-dark mb-1">Opening Hours</h3>
                            <p class="text-gray-600">
                                Monday - Friday: 7:00 AM - 10:00 PM<br>
                                Saturday - Sunday: 8:00 AM - 11:00 PM
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ================= SOCIAL MEDIA ================= -->
                <div class="mt-8">
                    <h3 class="font-bold text-coffee-dark mb-4">Follow Us</h3>

                    {{-- Icon media sosial --}}
                    <div class="flex gap-4">
                        {{-- Facebook --}}
                        <a href="#" class="w-12 h-12 bg-coffee-dark rounded-full flex items-center justify-center text-white hover:bg-gold transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20v-7.21h-2.42V9.25z"/>
                            </svg>
                        </a>

                        {{-- Instagram --}}
                        <a href="#" class="w-12 h-12 bg-coffee-dark rounded-full flex items-center justify-center text-white hover:bg-gold transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012z"/>
                            </svg>
                        </a>

                        {{-- Twitter --}}
                        <a href="#" class="w-12 h-12 bg-coffee-dark rounded-full flex items-center justify-center text-white hover:bg-gold transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825z"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- ================= GOOGLE MAPS ================= -->
<!-- Lokasi CoffPOS -->
<section class="py-16 bg-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Judul maps --}}
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold font-heading text-coffee-dark mb-4">
                Find Us
            </h2>
            <p class="text-gray-600">
                Visit our coffee shop and enjoy the perfect cup
            </p>
        </div>

        <!-- Embed Google Maps -->
        <div class="rounded-2xl overflow-hidden shadow-xl">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!2d107.6187093!3d-6.9147449"
                width="100%"
                height="450"
                style="border:0;"
                allowfullscreen
                loading="lazy">
            </iframe>
        </div>

        {{-- Tombol buka Google Maps --}}
        <div class="mt-8 text-center">
            <a href="https://maps.app.goo.gl/TvqEeZ3BMfmFKume6"
               target="_blank"
               class="inline-block bg-gold text-coffee-dark px-8 py-3 rounded-full font-semibold hover:bg-light-coffee transition">
                Get Directions →
            </a>
        </div>
    </div>
</section>
@endsection
