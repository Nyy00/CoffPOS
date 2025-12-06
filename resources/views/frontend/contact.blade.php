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
        <div class="grid md:grid-cols-2 gap-12">
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
                            <span class="text-2xl">📍</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-bold text-coffee-dark mb-1">Address</h3>
                            <p class="text-gray-600">Jl. Merdeka No. 123<br>Bandung, West Java 40111</p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl">📞</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-bold text-coffee-dark mb-1">Phone</h3>
                            <p class="text-gray-600">+62 812-3456-7890</p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl">✉️</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-bold text-coffee-dark mb-1">Email</h3>
                            <p class="text-gray-600">info@coffpos.com</p>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl">🕐</span>
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
                            <span class="text-xl">📘</span>
                        </a>
                        <a href="#" class="w-12 h-12 bg-coffee-dark rounded-full flex items-center justify-center text-white hover:bg-gold transition">
                            <span class="text-xl">📷</span>
                        </a>
                        <a href="#" class="w-12 h-12 bg-coffee-dark rounded-full flex items-center justify-center text-white hover:bg-gold transition">
                            <span class="text-xl">🐦</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <h2 class="text-2xl font-bold font-heading text-coffee-dark mb-6">Send us a Message</h2>
                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-semibold text-coffee-dark mb-2">Name</label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-coffee-dark mb-2">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-coffee-dark mb-2">Phone</label>
                        <input type="tel" id="phone" name="phone"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-coffee-dark mb-2">Message</label>
                        <textarea id="message" name="message" rows="4" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gold focus:border-transparent"></textarea>
                    </div>

                    <button type="submit" 
                        class="w-full bg-coffee-dark text-white py-3 rounded-lg font-semibold hover:bg-coffee-brown transition">
                        Send Message
                    </button>
                </form>
            </div>
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
            <a href="https://maps.google.com/?q=Bandung,West+Java" target="_blank" 
                class="inline-block bg-gold text-coffee-dark px-8 py-3 rounded-full font-semibold hover:bg-light-coffee transition">
                Get Directions →
            </a>
        </div>
    </div>
</section>
@endsection
