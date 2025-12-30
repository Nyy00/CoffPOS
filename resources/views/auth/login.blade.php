<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-coffee-dark">Selamat Datang Kembali!</h2>
        <p class="text-gray-600 mt-2">Masuk untuk mengakses akun Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div id="email-error" class="mt-2 text-sm text-red-600 hidden"></div>
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />

            <div class="relative mt-1">
                <x-text-input id="password" class="block w-full pr-10"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                
                <button type="button" onclick="togglePassword()" 
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-coffee-dark focus:outline-none">
                    <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="eye-slash-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <div id="password-error" class="mt-2 text-sm text-red-600 hidden"></div>
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-coffee-brown shadow-sm focus:ring-gold" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        <div class="mt-6">
            <button type="submit" id="loginButton" class="w-full bg-coffee-dark text-white py-3 rounded-lg font-semibold hover:bg-coffee-brown transition disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="loginButtonText">Masuk</span>
                <svg id="loginSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>

        <div class="flex items-center my-4">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="flex-shrink-0 mx-4 text-gray-600 text-sm">Atau lanjutkan dengan</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
            <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                    <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z" />
                    <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z" />
                    <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.734 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z" />
                    <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.799 L -6.734 42.379 C -8.804 40.439 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z" />
                </g>
            </svg>
            Masuk dengan Google
        </a>
        
        <div class="mt-4 text-center text-sm">
            <span class="text-gray-600">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-gold font-semibold hover:text-coffee-brown">
                Daftar di sini
            </a>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-coffee-dark flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </form>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }

        // Simple form validation and submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const loginButton = document.getElementById('loginButton');
            const loginButtonText = document.getElementById('loginButtonText');
            const loginSpinner = document.getElementById('loginSpinner');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');

            let isSubmitting = false;

            // Real-time email validation
            emailInput.addEventListener('blur', validateEmail);
            emailInput.addEventListener('input', function() {
                if (emailError.textContent) validateEmail();
            });

            // Real-time password validation
            passwordInput.addEventListener('blur', validatePassword);
            passwordInput.addEventListener('input', function() {
                if (passwordError.textContent) validatePassword();
            });

            // Form submission - use normal form submission for reliability
            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return;
                }
                
                // Clear previous errors
                clearErrors();
                
                // Validate all fields
                const isEmailValid = validateEmail();
                const isPasswordValid = validatePassword();
                
                if (!isEmailValid || !isPasswordValid) {
                    e.preventDefault();
                    return;
                }
                
                // Show loading state
                isSubmitting = true;
                setLoadingState(true);
                
                // Let the form submit normally - no AJAX to avoid timeout issues
                // The form will submit and redirect naturally
            });

            function validateEmail() {
                const email = emailInput.value.trim();
                
                if (!email) {
                    showError(emailError, 'Email wajib diisi');
                    return false;
                }
                
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showError(emailError, 'Masukkan alamat email yang valid');
                    return false;
                }
                
                hideError(emailError);
                return true;
            }

            function validatePassword() {
                const password = passwordInput.value;
                
                if (!password) {
                    showError(passwordError, 'Kata sandi wajib diisi');
                    return false;
                }
                
                if (password.length < 6) {
                    showError(passwordError, 'Kata sandi minimal 6 karakter');
                    return false;
                }
                
                hideError(passwordError);
                return true;
            }

            function showError(errorElement, message) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
                errorElement.parentElement.querySelector('input').classList.add('border-red-500');
            }

            function hideError(errorElement) {
                errorElement.textContent = '';
                errorElement.classList.add('hidden');
                errorElement.parentElement.querySelector('input').classList.remove('border-red-500');
            }

            function clearErrors() {
                hideError(emailError);
                hideError(passwordError);
            }

            function setLoadingState(loading) {
                if (loading) {
                    loginButton.disabled = true;
                    loginButtonText.textContent = 'Masuk...';
                    loginSpinner.classList.remove('hidden');
                } else {
                    loginButton.disabled = false;
                    loginButtonText.textContent = 'Masuk';
                    loginSpinner.classList.add('hidden');
                }
            }
        });
    </script>
</x-guest-layout>