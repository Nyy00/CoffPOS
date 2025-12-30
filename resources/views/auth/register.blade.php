<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-coffee-dark">Buat Akun</h2>
        <p class="text-gray-600 mt-2">Bergabunglah dengan komunitas kopi kami hari ini</p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
            <div id="name-error" class="mt-2 text-sm text-red-600 hidden"></div>
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div id="email-error" class="mt-2 text-sm text-red-600 hidden"></div>
        </div>

        <div class="mt-4">
            <x-input-label for="phone" value="Telepon (Opsional)" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" autocomplete="tel" placeholder="contoh: 08123456789" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            <div id="phone-error" class="mt-2 text-sm text-red-600 hidden"></div>
            <p class="mt-1 text-xs text-gray-500">Nomor telepon hanya boleh berisi angka (0-9)</p>
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />

            <div class="relative mt-1">
                <x-text-input id="password" class="block w-full pr-10"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                
                <button type="button" onclick="toggleRegisterPassword('password', 'eye-pass', 'slash-pass')" 
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-coffee-dark focus:outline-none">
                    <svg id="eye-pass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="slash-pass" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <div id="password-error" class="mt-2 text-sm text-red-600 hidden"></div>
            <div id="password-strength" class="mt-1 text-xs text-gray-500">
                Kata sandi minimal 8 karakter
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />

            <div class="relative mt-1">
                <x-text-input id="password_confirmation" class="block w-full pr-10"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                <button type="button" onclick="toggleRegisterPassword('password_confirmation', 'eye-conf', 'slash-conf')" 
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-coffee-dark focus:outline-none">
                    <svg id="eye-conf" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="slash-conf" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            <div id="password-confirmation-error" class="mt-2 text-sm text-red-600 hidden"></div>
        </div>

        <div class="mt-6">
            <button type="submit" id="registerButton" class="w-full bg-coffee-dark text-white py-3 rounded-lg font-semibold hover:bg-coffee-brown transition disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="registerButtonText">Daftar</span>
                <svg id="registerSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>

        <div class="mt-4">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Atau daftar dengan</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ url('auth/google') }}" class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                    <img class="h-5 w-5 mr-2" src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google Logo">
                    Daftar dengan Google
                </a>
            </div>
        </div>

        <div class="mt-6 text-center text-sm">
            <span class="text-gray-600">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="text-gold font-semibold hover:text-coffee-brown">
                Masuk di sini
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
        function toggleRegisterPassword(inputId, eyeId, slashId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            const slash = document.getElementById(slashId);

            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.add('hidden');
                slash.classList.remove('hidden');
            } else {
                input.type = 'password';
                eye.classList.remove('hidden');
                slash.classList.add('hidden');
            }
        }

        // Form validation and error handling
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const phoneInput = document.getElementById('phone');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const registerButton = document.getElementById('registerButton');
            const registerButtonText = document.getElementById('registerButtonText');
            const registerSpinner = document.getElementById('registerSpinner');
            
            // Error elements
            const nameError = document.getElementById('name-error');
            const emailError = document.getElementById('email-error');
            const phoneError = document.getElementById('phone-error');
            const passwordError = document.getElementById('password-error');
            const passwordConfirmationError = document.getElementById('password-confirmation-error');
            const passwordStrength = document.getElementById('password-strength');

            let isSubmitting = false;

            // Phone number validation - only allow numbers
            phoneInput.addEventListener('input', function(e) {
                // Remove any non-numeric characters
                let value = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = value;
                
                // Validate phone if there's content
                if (phoneError.textContent || value.length > 0) {
                    validatePhone();
                }
            });

            phoneInput.addEventListener('keypress', function(e) {
                // Only allow numbers (0-9)
                const char = String.fromCharCode(e.which);
                if (!/[0-9]/.test(char)) {
                    e.preventDefault();
                }
            });

            // Real-time validation
            nameInput.addEventListener('blur', validateName);
            nameInput.addEventListener('input', function() {
                if (nameError.textContent) validateName();
            });

            emailInput.addEventListener('blur', validateEmail);
            emailInput.addEventListener('input', function() {
                if (emailError.textContent) validateEmail();
            });

            phoneInput.addEventListener('blur', validatePhone);

            passwordInput.addEventListener('blur', validatePassword);
            passwordInput.addEventListener('input', function() {
                validatePassword();
                if (passwordConfirmationInput.value) {
                    validatePasswordConfirmation();
                }
            });

            passwordConfirmationInput.addEventListener('blur', validatePasswordConfirmation);
            passwordConfirmationInput.addEventListener('input', function() {
                if (passwordConfirmationError.textContent) validatePasswordConfirmation();
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
                const isNameValid = validateName();
                const isEmailValid = validateEmail();
                const isPhoneValid = validatePhone();
                const isPasswordValid = validatePassword();
                const isPasswordConfirmationValid = validatePasswordConfirmation();
                
                if (!isNameValid || !isEmailValid || !isPhoneValid || !isPasswordValid || !isPasswordConfirmationValid) {
                    e.preventDefault();
                    return;
                }
                
                // Show loading state
                isSubmitting = true;
                setLoadingState(true);
                
                // Let the form submit normally - no AJAX to avoid timeout issues
                // The form will submit and redirect naturally
            });

            function validateName() {
                const name = nameInput.value.trim();
                
                if (!name) {
                    showError(nameError, 'Nama wajib diisi');
                    return false;
                }
                
                if (name.length < 2) {
                    showError(nameError, 'Nama minimal 2 karakter');
                    return false;
                }
                
                if (name.length > 50) {
                    showError(nameError, 'Nama maksimal 50 karakter');
                    return false;
                }
                
                hideError(nameError);
                return true;
            }

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

            function validatePhone() {
                const phone = phoneInput.value.trim();
                
                // Phone is optional, so empty is valid
                if (!phone) {
                    hideError(phoneError);
                    return true;
                }
                
                // Check if phone contains only numbers
                if (!/^\d+$/.test(phone)) {
                    showError(phoneError, 'Nomor telepon hanya boleh berisi angka');
                    return false;
                }
                
                // Check minimum length
                if (phone.length < 10) {
                    showError(phoneError, 'Nomor telepon minimal 10 digit');
                    return false;
                }
                
                // Check maximum length
                if (phone.length > 15) {
                    showError(phoneError, 'Nomor telepon maksimal 15 digit');
                    return false;
                }
                
                hideError(phoneError);
                return true;
            }

            function validatePassword() {
                const password = passwordInput.value;
                
                if (!password) {
                    showError(passwordError, 'Kata sandi wajib diisi');
                    updatePasswordStrength('Kata sandi wajib diisi', 'text-red-500');
                    return false;
                }
                
                if (password.length < 8) {
                    showError(passwordError, 'Kata sandi minimal 8 karakter');
                    updatePasswordStrength('Kata sandi minimal 8 karakter', 'text-red-500');
                    return false;
                }
                
                // Check password strength
                let strength = 0;
                let strengthText = '';
                let strengthColor = '';
                
                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
                
                switch (strength) {
                    case 1:
                    case 2:
                        strengthText = 'Kata sandi lemah';
                        strengthColor = 'text-red-500';
                        break;
                    case 3:
                        strengthText = 'Kata sandi sedang';
                        strengthColor = 'text-yellow-500';
                        break;
                    case 4:
                    case 5:
                        strengthText = 'Kata sandi kuat';
                        strengthColor = 'text-green-500';
                        break;
                }
                
                updatePasswordStrength(strengthText, strengthColor);
                hideError(passwordError);
                return true;
            }

            function validatePasswordConfirmation() {
                const password = passwordInput.value;
                const passwordConfirmation = passwordConfirmationInput.value;
                
                if (!passwordConfirmation) {
                    showError(passwordConfirmationError, 'Konfirmasi kata sandi wajib diisi');
                    return false;
                }
                
                if (password !== passwordConfirmation) {
                    showError(passwordConfirmationError, 'Kata sandi tidak cocok');
                    return false;
                }
                
                hideError(passwordConfirmationError);
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

            function updatePasswordStrength(text, colorClass) {
                passwordStrength.textContent = text;
                passwordStrength.className = `mt-1 text-xs ${colorClass}`;
            }

            function clearErrors() {
                hideError(nameError);
                hideError(emailError);
                hideError(phoneError);
                hideError(passwordError);
                hideError(passwordConfirmationError);
            }

            function displayServerErrors(errors) {
                if (errors.name) showError(nameError, errors.name[0]);
                if (errors.email) showError(emailError, errors.email[0]);
                if (errors.phone) showError(phoneError, errors.phone[0]);
                if (errors.password) showError(passwordError, errors.password[0]);
                if (errors.password_confirmation) showError(passwordConfirmationError, errors.password_confirmation[0]);
            }

            function setLoadingState(loading) {
                if (loading) {
                    registerButton.disabled = true;
                    registerButtonText.textContent = 'Membuat Akun...';
                    registerSpinner.classList.remove('hidden');
                } else {
                    registerButton.disabled = false;
                    registerButtonText.textContent = 'Daftar';
                    registerSpinner.classList.add('hidden');
                }
            }
        });
    </script>
</x-guest-layout>