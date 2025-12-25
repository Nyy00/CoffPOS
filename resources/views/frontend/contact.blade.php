@extends('layouts.frontend')

@section('title', 'Contact Us')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-coffee-dark to-coffee-brown text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl font-bold font-heading mb-4">Contact Us</h1>
        <p class="text-xl text-cream/90">We'd love to hear from you</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-1 gap-12">
            <!-- Contact Info -->
            <div>
                <h2 class="text-3xl font-bold font-heading text-coffee-dark mb-6">Get in Touch</h2>
                <p class="text-gray-600 mb-8">
                    Have a question or want to visit us? We're here to help! 
                    Feel free to reach out through any of the following channels.
                </p>

                <div class="space-y-6">
                    <!-- Address -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-bold text-coffee-dark mb-1">Address</h3>
                            <p class="text-gray-600">Jl. Merdeka No. 123<br>Bandung, West Java 40111</p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948-.684l1.498-4.493a1 1 0 011.502-.684l1.498 4.493a1 1 0 00.948.684H19a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-bold text-coffee-dark mb-1">Phone</h3>
                            <p class="text-gray-600">+62 812-3456-7890</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-bold text-coffee-dark mb-1">Email</h3>
                            <p class="text-gray-600">info@coffpos.com</p>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-coffee-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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

                <!-- Social Media -->
                <div class="mt-8">
                    <h3 class="font-bold text-coffee-dark mb-4">Follow Us</h3>
                    <div class="flex gap-4">
                        <a href="#" class="w-12 h-12 bg-coffee-dark rounded-full flex items-center justify-center text-white hover:bg-gold transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20v-7.21h-2.42V9.25h2.42V7.07c0-2.49 1.52-3.89 3.75-3.89 1.07 0 1.99.08 2.25.11v2.60h-1.54c-1.22 0-1.46.58-1.46 1.42v1.86h2.92l-.38 3.54h-2.54V20"/>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-coffee-dark rounded-full flex items-center justify-center text-white hover:bg-gold transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.057-1.645.069-4.849.069-3.205 0-3.584-.012-4.849-.069-3.259-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.322a1.44 1.44 0 110-2.88 1.44 1.44 0 010 2.88z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-coffee-dark rounded-full flex items-center justify-center text-white hover:bg-gold transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417a9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact form removed -->
        </div>
    </div>
</section>

<!-- Google Maps -->
<section class="py-16 bg-cream">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold font-heading text-coffee-dark mb-4">Find Us</h2>
            <p class="text-gray-600">Visit our coffee shop and enjoy the perfect cup</p>
        </div>

        <!-- Google Maps Embed -->
        <div class="rounded-2xl overflow-hidden shadow-xl">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.798464181358!2d107.61870931477394!3d-6.914744995006447!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a1f93d3e815b2!2sBandung%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1234567890123!5m2!1sen!2sid" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <div class="mt-8 text-center">
            <a href="https://maps.app.goo.gl/TvqEeZ3BMfmFKume6" target="_blank" 
                class="inline-block bg-gold text-coffee-dark px-8 py-3 rounded-full font-semibold hover:bg-light-coffee transition">
                Get Directions →
            </a>
        </div>
    </div>
</section>
@endsection